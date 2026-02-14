<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Staff;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // 1. 日付取得
        $selectedDate = $request->input('date', date('Y-m-d'));

        // 2. スタッフ取得
        $realStaffs = Staff::orderBy('id', 'asc')->get();

        // 「指名なし」スタッフ作成
        $noNominationStaff = new Staff();
        $noNominationStaff->id = 0;
        $noNominationStaff->name = '指名なし';

        // スタッフリスト結合
        $staffs = $realStaffs->push($noNominationStaff);

        // 曜日番号
        $dayOfWeek = date('w', strtotime($selectedDate));

        // 定休日マップ
        $holidayMap = [
            0 => [2],    // 指名なし
            1 => [2, 3], // Hikaru
            2 => [2, 1], // Yuki
            3 => [2, 4], // Ken
        ];

        // 各スタッフに「今日休み？」フラグ
        foreach ($staffs as $staff) {
            if (in_array($dayOfWeek, $holidayMap[$staff->id] ?? [])) {
                $staff->is_holiday = true;
            } else {
                $staff->is_holiday = false;
            }
        }

        // 3. 予約データ取得
        $reservations = Reservation::with(['user', 'menus', 'staff'])
            ->where('reservation_date', $selectedDate)
            ->get();

        // 4. 本日の総売上
        $todayTotal = 0;
        $processedIds = [];

        foreach ($reservations as $res) {
            if (!in_array($res->id, $processedIds)) {
                $todayTotal += $res->menus->sum('price');
                $processedIds[] = $res->id;
            }
        }

        // 5. タイムライン作成（修正：時間をまたぐ処理）
        $timeline = [];
        foreach ($reservations as $res) {
            // メニューの合計時間（分）を取得
            $duration = $res->menus->sum('duration');
            // 30分単位で何コマ必要か計算（切り上げ）
            $slots = ceil($duration / 30);

            // 開始時間
            $startTime = strtotime($res->reservation_time);

            // 必要なコマ数分だけループして埋める
            for ($i = 0; $i < $slots; $i++) {
                // 30分ずつずらす
                $currentTime = $startTime + ($i * 1800);
                $timeKey = date('H:i', $currentTime);

                $staffId = $res->staff_id ?? 0;
                $timeline[$timeKey][$staffId][] = $res;
            }
        }

        // 6. 時間枠作成
        $timeSlots = [];
        $start = strtotime('10:00');
        $end = strtotime('20:00');
        for ($t = $start; $t <= $end; $t += 1800) {
            $timeSlots[] = date('H:i', $t);
        }

        // ==========================================
        // 売上チャート用のデータ計算
        // ==========================================
        $currentYear = date('Y');
        $yearlyReservations = Reservation::with(['menus', 'staff'])
            ->whereYear('reservation_date', $currentYear)
            ->get();

        // 月別売上
        $monthlySales = array_fill(1, 12, 0);
        $processedIdsForChart = [];

        foreach ($yearlyReservations as $res) {
            if (!in_array($res->id, $processedIdsForChart)) {
                $month = (int)date('m', strtotime($res->reservation_date));
                $sales = $res->menus->sum('price');
                $monthlySales[$month] += $sales;
                $processedIdsForChart[] = $res->id;
            }
        }

        // スタッフ別売上（修正：指名なし対応）
        $staffSales = [];
        foreach ($staffs as $s) {
            $staffSales[$s->name] = 0;
        }
        $processedIdsForStaffChart = [];

        foreach ($yearlyReservations as $res) {
            if (!in_array($res->id, $processedIdsForStaffChart)) {
                $sales = $res->menus->sum('price');

                // 指名料フラグ(is_nominated)を見て振り分け
                if ($res->is_nominated == 0) {
                    $staffName = '指名なし';
                } else {
                    $staffName = $res->staff ? $res->staff->name : '指名なし';
                }

                if (isset($staffSales[$staffName])) {
                    $staffSales[$staffName] += $sales;
                } else {
                    $staffSales['指名なし'] += $sales;
                }
                $processedIdsForStaffChart[] = $res->id;
            }
        }

        // グラフ用データ
        $monthlyLabels = array_keys($monthlySales);
        $monthlyValues = array_values($monthlySales);
        $staffLabels = array_keys($staffSales);
        $staffValues = array_values($staffSales);

        return view('admin.dashboard', compact(
            'timeline',
            'timeSlots',
            'staffs',
            'selectedDate',
            'todayTotal',
            'monthlyLabels',
            'monthlyValues',
            'staffLabels',
            'staffValues',
            'currentYear'
        ));
    }

    // 電話予約
    public function store(Request $request)
    {
        $request->validate([
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'menu_ids' => 'required|array',
            'staff_id' => 'required',
        ]);

        $phoneUser = \App\Models\User::where('email', 'phone@phone')->first();
        if (!$phoneUser) {
            return back()->with('error', '電話予約用のユーザーが見つかりません。');
        }

        // ★追加：同時間の予約数をチェック（4件制限）
        $count = Reservation::where('reservation_date', $request->reservation_date)
            ->where('reservation_time', $request->reservation_time)
            ->count();

        // 4件以上ならエラー
        if ($count >= 4) {
            return back()->with('error', 'この時間は既に満席（4名）です。これ以上予約できません。');
        }

        // 指名判定
        $isNominated = $request->staff_id != 0;
        $dbStaffId = ($request->staff_id == 0) ? null : $request->staff_id;

        $reservation = Reservation::create([
            'user_id' => $phoneUser->id,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'staff_id' => $dbStaffId,
            'is_nominated' => $isNominated,
        ]);

        $reservation->menus()->attach($request->menu_ids);

        return back()->with('success', '電話予約を登録しました');
    }

    // アサイン（担当変更）
    public function assign(Request $request, $id)
    {
        $baseReservation = Reservation::findOrFail($id);

        // 担当者のみ変更（is_nominatedは触らない）
        Reservation::where('reservation_date', $baseReservation->reservation_date)
            ->where('user_id', $baseReservation->user_id)
            ->where('created_at', $baseReservation->created_at)
            ->update([
                'staff_id' => $request->input('staff_id')
            ]);

        return redirect()->route('admin.dashboard', ['date' => $baseReservation->reservation_date])
            ->with('success', 'スタッフを割り当てました');
    }

    // 削除
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);

        Reservation::where('user_id', $reservation->user_id)
            ->where('reservation_date', $reservation->reservation_date)
            ->where('created_at', $reservation->created_at)
            ->delete();

        return back()->with('success', '予約を完全に削除しました。');
    }
}
