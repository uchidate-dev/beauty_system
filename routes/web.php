<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
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

// 管理者(admin)のみが入れるルートグループ
Route::middleware(['auth', 'can:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

// routes/web.php に追記（AdminControllerのグループ内など）
Route::patch('/admin/reservations/{id}/assign', [App\Http\Controllers\AdminController::class, 'assign'])->name('admin.reservations.assign');

// adminが行う予約キャンセルボタン
Route::delete('/admin/reservations/{id}', [App\Http\Controllers\AdminController::class, 'destroy'])->name('admin.reservations.destroy');

require __DIR__ . '/auth.php';
