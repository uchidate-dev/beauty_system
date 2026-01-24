<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Menu;

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

        // 次の画面（日時選択）を表示（まだファイルがないのでまずはデータ確認）
        return view('reservations.datetime', compact('menus', 'staff'));
    }
}
