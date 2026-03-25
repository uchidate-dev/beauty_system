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
        $password = Hash::make('123456789');

        // 1. システム必須ユーザー
        $coreUsers = [
            ['name' => '電話予約', 'email' => 'phone@phone', 'role' => 'user', 'is_admin' => 0],
            ['name' => 'サロン管理者', 'email' => 'admin@admin', 'role' => 'admin', 'is_admin' => 1],
            ['name' => 'デモユーザー', 'email' => 'test@test', 'role' => 'user', 'is_admin' => 0], // デモ用合鍵
        ];

        foreach ($coreUsers as $user) {
            DB::table('users')->insertOrIgnore(array_merge($user, [
                'password' => $password,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 2. ダミーお客さん10名
        $dummyNames = [
            '佐藤 栞',
            '鈴木 結衣',
            '高橋 美咲',
            '田中 さくら',
            '伊藤 凛',
            '渡辺 陽菜',
            '山本 楓',
            '中村 リオ',
            '小林 美羽',
            '加藤 彩乃'
        ];

        foreach ($dummyNames as $index => $name) {
            DB::table('users')->insertOrIgnore([
                'name' => $name,
                'email' => 'dummy' . ($index + 1) . '@test',
                'password' => $password,
                'role' => 'user',
                'is_admin' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
