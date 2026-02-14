<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // 1. 電話予約用
        DB::table('users')->insertOrIgnore([
            'name' => '電話予約',
            'email' => 'phone@phone',
            'password' => Hash::make('123456789'),
            'role' => 'user',
            'is_admin' => 0,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        // 2. 舞子さん用 管理者
        DB::table('users')->insertOrIgnore([
            'name' => '管理者',
            'email' => 'admin@admin',
            'password' => Hash::make('123456789'),
            'role' => 'admin',
            'is_admin' => 1,
            'created_at' => $now, 'updated_at' => $now,
        ]);
    }
}
