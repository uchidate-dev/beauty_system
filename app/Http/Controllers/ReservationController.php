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
}
