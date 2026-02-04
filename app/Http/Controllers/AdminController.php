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
        // データベースにいる本物のスタッフを取得
        $realStaffs = Staff::orderBy('id', 'asc')->get();

        // 「指名なし」という架空のスタッフを手動で作成
        $noNominationStaff = new Staff();
        $noNominationStaff->id = 0;
        $noNominationStaff->name = '指名なし';

        // 本物のスタッフリストの「後ろ」に指名なしを追加
        $staffs = $realStaffs->push($noNominationStaff);

        // 曜日番号を取得 (0:日, 1:月, 2:火, 3:水, 4:木, 5:金, 6:土)
        $dayOfWeek = date('w', strtotime($selectedDate));

        // 定休日マップ
        // ※0(指名なし)は火曜日を指定する
        $holidayMap = [
            0 => [2],
            1 => [2, 3],
            2 => [2, 1],
            3 => [2, 4],
        ];

        // 各スタッフに「今日休み？」フラグをつける
        foreach ($staffs as $staff) {
            // IDが0の場合も含めて、マップにその曜日が含まれているかチェック
            if (in_array($dayOfWeek, $holidayMap[$staff->id] ?? [])) {
                $staff->is_holiday = true;
            } else {
                $staff->is_holiday = false;
            }
        }

        // 3. 予約データ取得
        $reservations = Reservation::with(['user', 'menus'])
            ->where('reservation_date', $selectedDate)
            ->get();

        //  4. 本日の総売上を計算（重複排除版）
        $todayTotal = 0;
        // 予約IDを記録する箱を用意
        $processedIds = [];

        foreach ($reservations as $res) {
            // まだ計算していない予約IDの場合だけ足す
            if (!in_array($res->id, $processedIds)) {
                $todayTotal += $res->menus->sum('price');
                // 計算済みリストにIDを追加
                $processedIds[] = $res->id;
            }
        }

        //  5. タイムライン作成
        $timeline = [];
        foreach ($reservations as $res) {
            $time = date('H:i', strtotime($res->reservation_time));

            // データベースの staff_idがNULLの場合は、0(指名なし列)に入れる
            // "??"は「もし左がNULLなら右を使う」という便利な書き方
            $staffId = $res->staff_id ?? 0;
            $timeline[$time][$staffId][] = $res;
        }

        //  6. 時間枠作成
        $timeSlots = [];
        $start = strtotime('10:00');
        $end = strtotime('20:00');
        for ($t = $start; $t <= $end; $t += 1800) {
            $timeSlots[] = date('H:i', $t);
        }

        return view('admin.dashboard', compact(
            'timeline',
            'timeSlots',
            'staffs',
            'selectedDate',
            'todayTotal'
        ));
    }

    public function assign(Request $request, $id)
    {
        $baseReservation = Reservation::findOrFail($id);
        Reservation::where('reservation_date', $baseReservation->reservation_date)
            ->where('user_id', $baseReservation->user_id)
            ->where('created_at', $baseReservation->created_at)
            ->update([
                'staff_id' => $request->input('staff_id')
            ]);

        return redirect()->route('admin.dashboard', ['date' => $baseReservation->reservation_date])
            ->with('success', 'スタッフを割り当てました');
    }

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
