<?php

namespace App\Http\Controllers;
// 予約モデルをインポート
use App\Models\Reservation;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // 全ての予約を、日付が新しい順に取得
        $reservations = Reservation::with(['user', 'menus'])
        ->orderBy('reservation_date', 'desc')->get();

        return view('admin.dashboard', compact('reservations'));
    }
}
