<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // IDを指定してメニューを登録（これでズレない！）
        DB::table('menus')->insert([
            ['id' => 1, 'name' => 'カット', 'price' => 5000, 'duration' => 60, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'カラー', 'price' => 7000, 'duration' => 90, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'カット＋カラー', 'price' => 11000, 'duration' => 120, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'トリートメント', 'price' => 3000, 'duration' => 30, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'パーマ', 'price' => 8000, 'duration' => 120, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'name' => '縮毛矯正', 'price' => 11000, 'duration' => 120, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
