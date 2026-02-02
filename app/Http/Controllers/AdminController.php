<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // 1. 日付取得
        $selectedDate = $request->input('date', date('Y-m-d'));

        // 2. スタッフ取得
        $staffs = \App\Models\Staff::orderBy('id', 'asc')->get()->unique('name');

        // 3. 予約データ取得
        $reservations = \App\Models\Reservation::with(['user', 'menus'])
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
            $timeline[$time][$res->staff_id] = $res;
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
        $baseReservation = \App\Models\Reservation::findOrFail($id);
        \App\Models\Reservation::where('reservation_date', $baseReservation->reservation_date)
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
        $reservation = \App\Models\Reservation::findOrFail($id);
        \App\Models\Reservation::where('user_id', $reservation->user_id)
            ->where('reservation_date', $reservation->reservation_date)
            ->where('created_at', $reservation->created_at)
            ->delete();

        return back()->with('success', '予約を完全に削除しました。');
    }
}
