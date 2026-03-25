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

        // 1. 電話予約用（管理者代理入力用・システム必須）
        DB::table('users')->insertOrIgnore([
            'name' => '電話予約',
            'email' => 'phone@phone',
            'password' => Hash::make('123456789'),
            'role' => 'user',
            'is_admin' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 2. 管理者(売上グラフや予約管理などの確認用)
        DB::table('users')->insertOrIgnore([
            'name' => '管理者',
            'email' => 'admin@admin',
            'password' => Hash::make('123456789'),
            'role' => 'admin',
            'is_admin' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 3. テスト顧客(UIを体験するためのユーザー)
        DB::table('users')->insertOrIgnore([
            'name' => 'デモユーザー',
            'email' => 'test@test',
            'password' => Hash::make('123456789'),
            'role' => 'user',
            'is_admin' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
