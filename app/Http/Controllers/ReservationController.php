<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Menu;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        // データベースから全スタッフと全メニューを取得
        $staffs = Staff::whereIn('id', [1, 2, 3])->get();
        $menus = Menu::all();

        return view('reservations.index', compact('staffs', 'menus'));
    }

    // 予約日時選択画面を表示するメソッド（既存の showDateTime を書き換え）
    public function showDateTime(Request $request)
    {
        // 画面で選んだメニューとスタッフのIDを受け取る
        $selectedMenuIds = $request->input('menu_ids');
        $selectedStaffId = $request->input('staff_id');

        // メニューが選ばれていない場合は前の画面に戻す
        if (empty($selectedMenuIds)) {
            return back()->with('error', 'メニューを1つ以上選択してください。');
        }

        // IDから実際のデータを取得
        $menus = Menu::whereIn('id', $selectedMenuIds)->get();

        // スタッフ情報の取得（指名なし 0 の場合も考慮）
        if ($selectedStaffId == 0) {
            // 指名なしの場合：DBを通さず、その場で「指名なし」というスタッフを作る
            $staff = new Staff();
            $staff->id = 0;
            $staff->name = '指名なし';
        } else {
            // 指名ありの場合：DBから本物のスタッフを探してくる
            $staff = Staff::findOrFail($selectedStaffId);
        }

        return view('reservations.datetime', compact('menus', 'staff'));
    }

    // Ajaxから呼ばれて「1週間分の予約済みリスト」を返すメソッド
    public function checkWeekAvailability(Request $request)
    {
        $staffId = $request->staff_id; // ユーザーが指名しようとしているスタッフ（0なら指名なし）
        $startDate = $request->start_date ?: date('Y-m-d');
        $endDate = date('Y-m-d', strtotime($startDate . ' +6 days'));

        $reservations = Reservation::whereBetween('reservation_date', [$startDate, $endDate])->get();

        // 集計処理
        $totalUsage = []; // お店全体の予約数（指名なし含む)
        $staffUsage = []; // スタッフごとの予約数

        foreach ($reservations as $res) {
            $date = $res->reservation_date;
            $time = date('H:i', strtotime($res->reservation_time));

            // A. お店全体のカウント
            $totalUsage[$date][$time] = ($totalUsage[$date][$time] ?? 0) + 1;

            // B. スタッフ個別のカウント(指名されている場合)
            if ($res->staff_id) { // staff_id がNULL(指名なし) じゃなければ
                $staffUsage[$res->staff_id][$date][$time] = ($staffUsage[$res->staff_id][$date][$time] ?? 0) + 1;
            }
        }

        // 3. JSに返す「予約不可リスト」を作成
        $bookedData = [];

        // 定休日・出勤人数のマップ
        $holidayMap = [
            1 => [2, 3], // Hikaru
            2 => [2, 1], // Yuki
            3 => [2, 4], // Ken
        ];

        // 期間中の各日付についてチェック
        $currentDate = $startDate;
        while (strtotime($currentDate) <= strtotime($endDate)) {
            $dateStr = date('Y-m-d', strtotime($currentDate));
            $dayOfWeek = date('w', strtotime($currentDate));

            // その日の最大キャパ数を計算
            $activeStaffCount = 0;
            foreach ([1, 2, 3] as $id) {
                if (!in_array($dayOfWeek, $holidayMap[$id] ?? [])) {
                    $activeStaffCount++;
                }
            }

            // その日に予約が入っている時間枠をループ
            if (isset($totalUsage[$dateStr])) {
                foreach ($totalUsage[$dateStr] as $time => $totalCount) {

                    $isFull = false;

                    // 【条件A】お店全体が満席か？
                    // 指名・指名なしに関わらず、予約総数がスタッフ人数に達していたらアウト
                    if ($totalCount >= $activeStaffCount) {
                        $isFull = true;
                    }

                    // 【条件B】指名したスタッフが埋まっているか？                
                    if ($staffId != 0 && !$isFull) {
                        // そのスタッフ個人の予約数をチェック
                        $myCount = $staffUsage[$staffId][$dateStr][$time] ?? 0;
                        if ($myCount >= 1) {
                            $isFull = true;
                        }
                    }

                    // 満席判定ならリストに追加
                    if ($isFull) {
                        $bookedData[$dateStr][] = $time;
                    }
                }
            }

            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }

        // 定休日情報の取得
        $staffHolidays = $holidayMap[$staffId] ?? [2];

        return response()->json([
            'booked' => $bookedData,
            'holidays' => $staffHolidays
        ]);
    }

    // 予約をデータベースに保存する
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', '予約にはログインが必要です');
        }

        // 送られてきたデータのバリデーション（チェック）
        $request->validate([
            'staff_id' => 'required',
            'menu_ids' => 'required|array',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
        ]);

        // 全体キャパシティ（その日の出勤スタッフ数）チェック

        $dayOfWeek = date('w', strtotime($request->reservation_date));
        $holidayMap = [
            1 => [2, 3], // Hikaru
            2 => [2, 1], // YUki
            3 => [2, 4], // Ken
        ];

        // 今日出勤しているスタッフのIDリストを作成
        $activeStaffIds = [];
        foreach ([1, 2, 3] as $id) {
            if (!in_array($dayOfWeek, $holidayMap[$id] ?? [])) {
                $activeStaffIds[] = $id;
            }
        }

        // お店の最大予約可能枠数（= 出勤スタッフ数）
        $maxCapacity = count($activeStaffIds);
        // その時間の「現在の予約総数」をカウント（指名・指名なし合算）
        $currentReservationsCount = Reservation::where('reservation_date', $request->reservation_date)
            ->where('reservation_time', $request->reservation_time)
            ->count();

        // 満席チェック
        if ($currentReservationsCount >= $maxCapacity) {
            return redirect()->route('reservations.index')
                ->with('error', '申し訳ございません。この時間は満席です。');
        }

        // 指名ありの場合の個別チェック
        $targetStaffId = $request->staff_id;

        if ($targetStaffId != 0) {
            // 指名されたスタッフがすでに埋まっていないかチェック
            $isStaffBooked = Reservation::where('staff_id', $targetStaffId)
                ->where('reservation_date', $request->reservation_date)
                ->where('reservation_time', $request->reservation_time)
                ->exists();

            if ($isStaffBooked) {
                return redirect()->route('reservations.index')
                    ->with('error', '申し訳ありません。ご指名のスタッフは既に予約が入っています。');
            }
        }

        // 保存処理
        // 指名なし(0)ならNULLにする、指名ありならそのままIDを使う
        $dbStaffId = ($targetStaffId == 0) ? null : $targetStaffId;

        // 合計所要時間を計算
        $selectedMenus = Menu::whereIn('id', $request->menu_ids)->get();
        $totalDuration = $selectedMenus->sum('duration');

        // コマ数を計算 (30分単位で切り上げ)
        $frameCount = ceil($totalDuration / 30);

        // ここで今の時間を固定
        $now = now();

        for ($i = 0; $i < $frameCount; $i++) {

            $reservation = new Reservation();
            $reservation->user_id = auth()->id();
            $reservation->staff_id = $dbStaffId;
            $reservation->reservation_date = $request->reservation_date;
            $startTime = strtotime($request->reservation_time);
            $reservation->reservation_time = date('H:i', $startTime + ($i * 1800));
            $reservation->status = 'pending';

            // 固定した時間を手動でセットする
            $reservation->created_at = $now;
            $reservation->updated_at = $now;

            $reservation->save();

            if ($i === 0) {

                // 2. 予約とメニューを紐付ける（多対多の場合）
                // ※中間テーブルがある前提です。もし単純な作りならここを調整します。
                $reservation->menus()->attach($request->menu_ids);
            }
        }

        // 3. 完了画面へリダイレクト
        return redirect()->route('reservations.thanks')
            ->with('success', '予約が完了しました！');
    }

    public function dashboard()
    {
        // 現在の「日時」を取得（例：2026-02-01 10：30：00）
        $now = now();

        $all = auth()->user()->reservations()
            ->with(['staff', 'menus'])
            ->has('menus')
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->get();

        $today = now()->format('Y-m-d');

        // 未来の予約： 「日付が明日以降」または「今日だけど、開始時間が今より後」

        $upcomingReservations = $all->filter(function ($res) use ($now) {
            $resDateTime = \Carbon\Carbon::parse($res->reservation_date . ' ' . $res->reservation_time);
            // 現在より未来ならtrue
            return $resDateTime->isFuture();
        })->sortBy('reservation_date');

        // 過去の予約 (来店履歴) : 現在より過去のもの
        $pastReservations = $all->filter(function ($res) use ($now) {
            $resDateTime = \Carbon\Carbon::parse($res->reservation_date . ' ' . $res->reservation_time);
            //現在より過去ならtrue
            return $resDateTime->isPast();
        })->sortByDesc('reservation_date');

        return view('dashboard', compact('upcomingReservations', 'pastReservations'));
    }

    public function destroy(Reservation $reservation)
    {
        // 本人の予約以外は消せないようにする
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        // 「同じ日、同じスタッフ、同じユーザー」で、
        // 「メインの予約（自分）以降の時間」にある予約（ダミーコマ含む）をまとめて消す
        Reservation::where('user_id', $reservation->user_id)
            ->where('staff_id', $reservation->staff_id)
            ->where('reservation_date', $reservation->reservation_date)
            ->where('reservation_time', '>=', $reservation->reservation_time)
            ->delete(); // メインもダミーもまとめて一気に削除！

        return redirect()->route('dashboard')->with('success', '予約をキャンセルしました。');
    }
}
