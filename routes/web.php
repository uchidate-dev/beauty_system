<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;

Route::get('/', function () {
    return view('welcome');
});

// ✅ ここを修正！Controllerを通すようにします
Route::get('/dashboard', [ReservationController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // プロフィール関連
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 予約関連（グループの中にまとめるとスッキリします）
    Route::get('/reserve', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reserve/datetime', [ReservationController::class, 'showDateTime'])->name('reservations.datetime');
    Route::post('/reserve/store', [ReservationController::class, 'store'])->name('reservations.store');

    // 予約キャンセル
    Route::delete('/reserve/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
});

// 1週間分の空き状況を取得するAPI
Route::get('/api/reservations/check-week', [ReservationController::class, 'checkWeekAvailability']);

// 完了画面
Route::get('/reserve/thanks', function () {
    return view('reservations.thanks');
})->name('reservations.thanks');



require __DIR__ . '/auth.php';
