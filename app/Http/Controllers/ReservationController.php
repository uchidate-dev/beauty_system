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
        $staffs = Staff::all();
        $menus = Menu::all();

        // 予約画面（これから作成）にデータを渡す
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
        // 1週間分の表はJSで作るのでここではスタッフとメニューを渡す
        if ($selectedStaffId == 0) {
            $staff = new Staff(['id' => 0, 'name' => '指名なし']);
        } else {
            $staff = Staff::findOrFail($selectedStaffId);
        }

        return view('reservations.datetime', compact('menus', 'staff'));
    }
    // Ajaxから呼ばれて「1週間分の予約済みリスト」を返すメソッド
    public function checkWeekAvailability(Request $request)
    {
        $staffId = $request->staff_id;
        // 今日から7日後までの範囲
        $startDate = $request->start_date ?: date('Y-m-d');
        $endDate = date('Y-m-d', strtotime($startDate . ' +6 days'));

        // 指定期間の予約データを取得
        $reservations = Reservation::where('staff_id', $staffId)
            ->whereBetween('reservation_date', [$startDate, $endDate])
            ->get();

        // JSが扱いやすいように 「日付 => [時間, 時間]」 の形に整理
        $bookedData = [];
        foreach ($reservations as $res) {
            // データベースの日付 (2026-01-26)
            $date = $res->reservation_date;
            // データベースの時間 (10:00:00 →10:00)
            $time = date('H:i', strtotime($res->reservation_time));
            $bookedData[$date][] = $time;
        }

        // 定休日設定 (0:日, 1:月, 2:火, 3:水, 4:木, 5:金, 6:土)
        // お店は火曜(2)休み
        $holidayMap = [
            'Hikaru' => [2, 3], // 火・水
            'Yuki'   => [2, 1], // 火・月
            'Ken'    => [2, 4], // 火・木
        ];

        // 指名なし(0)の場合は火曜(2)のみ休みとする
        $staff = Staff::find($staffId);
        $staffName = $staff ? $staff->name : 'Any';
        $staffHolidays = $holidayMap[$staffName] ?? [2];

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

        // 1. 予約の基本情報を保存
        $reservation = new Reservation();
        $reservation->user_id = auth()->id(); // ログイン中のユーザーID
        $reservation->staff_id = $request->staff_id;
        $reservation->reservation_date = $request->reservation_date;
        $reservation->reservation_time = $request->reservation_time;
        $reservation->status = 'pending'; // まずは「予約中」として保存
        $reservation->save();

        // 2. 予約とメニューを紐付ける（多対多の場合）
        // ※中間テーブルがある前提です。もし単純な作りならここを調整します。
        $reservation->menus()->attach($request->menu_ids);

        // 3. 完了画面へリダイレクト
        return redirect()->route('reservations.thanks')
            ->with('success', '予約が完了しました！');
    }

    public function dashboard()
    {
        // 全予約を取得（新しい順）
        $all = auth()->user()->reservations()
            ->with(['staff', 'menus'])
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->get();

        $today = now()->format('Y-m-d');

        // 未来の予約（今日を含む）
        $upcomingReservations = $all->where('reservation_date', '>=', $today)->reverse();
        // 過去の予約
        $pastReservations = $all->where('reservation_date', '<', $today);

        return view('dashboard', compact('upcomingReservations', 'pastReservations'));
    }

    public function destroy(Reservation $reservation)
    {
        // 本人の予約以外は消せないようにする
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        $reservation->delete();

        return redirect()->route('dashboard')->with('success', '予約をキャンセルしました。');
    }
}
