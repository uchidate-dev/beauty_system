<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;


Route::get('/', function () {
    return view('welcome');
});

// Controllerを通す
Route::get('/dashboard', [ReservationController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // プロフィール関連
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 予約関連
    Route::get('/reserve', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reserve/datetime', [ReservationController::class, 'showDateTime'])->name('reservations.datetime');
    Route::post('/reserve/store', [ReservationController::class, 'store'])->name('reservations.store');

    // 予約キャンセル
    Route::delete('/reserve/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
});

// 1週間分の空き状況を取得するAPI
Route::get('/api/reservations/check-week', [ReservationController::class, 'checkWeekAvailability']);

// 予約完了画面
Route::get('/reserve/thanks', function () {
    return view('reservations.thanks');
})->name('reservations.thanks');

// Book Nowの遷移先をログイン状態によって変える
Route::get('/booking-gate', function () {
    if (auth()->check()) {
        return redirect()->route('reservations.index');
    }
    // ログインしていなければ中間ページへ
    return view('auth.booking-gate');
})->name('booking.gate');

// ====================================================
// 管理者(admin)専用のルートグループ
// ====================================================
Route::middleware(['auth', 'can:admin'])->prefix('admin')->group(function () {

    // ダッシュボード表示
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // 電話予約（管理者が主導で予約作成）
    // URLは自動的に /admin/reservations になる
    Route::post('/reservations', [AdminController::class, 'store'])->name('admin.reservations.store');

    // スタッフのアサイン（担当者割り当て）
    Route::patch('/reservations/{id}/assign', [AdminController::class, 'assign'])->name('admin.reservations.assign');

    // 予約キャンセル（管理者権限で削除）
    Route::delete('/reservations/{id}', [AdminController::class, 'destroy'])->name('admin.reservations.destroy');
});



require __DIR__ . '/auth.php';
