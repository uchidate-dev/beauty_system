<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // プロフィール関連
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 予約の入り口（メニュー・スタッフ選択）
    Route::get('/reserve', [ReservationController::class, 'index'])->name('reservations.index');
    // 日時選択画面
    Route::get('/reserve/datetime', [ReservationController::class, 'showDateTime'])->name('reservations.datetime');
});

require __DIR__ . '/auth.php';
