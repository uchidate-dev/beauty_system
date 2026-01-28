<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'staff_id',
        'reservation_date',
        'reservation_time',
        'status',
    ];

    /**
     * 予約は1人のスタッフに紐づく
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * 予約は1人のユーザーに紐づく
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 予約は複数のメニューを持つ（中間テーブル経由）
     */

    public function menus()
    {
        // 第2引数を 'reservation_menu' に
        return $this->belongsToMany(Menu::class, 'reservation_menu');
    }
}
