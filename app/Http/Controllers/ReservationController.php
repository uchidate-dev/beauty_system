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

    public function showDateTime(Request $request)
    {
        // 画面で選んだメニューとスタッフのIDを受け取る
        $selectedMenuIds = $request->input('menu_ids');
        $selectedStaffId = $request->input('staff_id');

        // IDから実際のデータを取得
        $menus = Menu::whereIn('id', $selectedMenuIds)->get();
        $staff = Staff::find($selectedStaffId);

        // 30分刻みの時間枠を作成
        return view('reservations.datetime', compact('menus', 'staff'));
    }

    public function store(Request $request)
    {
        // 予約を保存（メニューが複数の場合も考え代表で1つ保存）
        $reservation = new Reservation();
        $reservation->user_id = auth()->id();
        $reservation->staff_id = $request->staff_id;
        $reservation->menu_id = $request->menu_ids[0]; // 最初のメニュー
        $reservation->reservation_date = $request->reservation_date;
        $reservation->reservation_time = $request->reservation_time;
        $reservation->status = 'pending';
        $reservation->save();

        return redirect()->route('dashboard')->with('success', '予約が完了しました！');
    }

}
