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

        // メニューが選ばれていない場合は前の画面に戻す
        if (empty($selectedMenuIds)) {
            return back()->with('error', 'メニューを1つ以上選択してください。');
        }

        // IDから実際のデータを取得
        $menus = Menu::whereIn('id', $selectedMenuIds)->get();
        // 指名なし(0)の場合はダミーのスタッフデータを作る
        if ($selectedStaffId == 0) {
            $staff = new \App\Models\Staff();
            $staff->id = 0;
            $staff->name = '指名なし';
        } else {
            $staff = Staff::find($selectedStaffId);
        }

        // 30分刻みの時間はそのままでOK
        $times = [];
        $start = new \DateTime('10:00');
        $end = new \DateTime('19:00');
        $interval = new \DateInterval('PT30M');

        while ($start <= $end) {
            $times[] = $start->format('H:i');
            $start->add($interval);
        }

        return view('reservations.datetime', compact('menus', 'staff', 'times'));
    }
}
