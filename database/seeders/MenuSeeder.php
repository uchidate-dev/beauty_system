<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //メニュー情報
        DB::table('menus')->insert([
            ['name' => 'カット', 'price' => 5000, 'duration' => 60],
            ['name' => 'カラー', 'price' => 7000, 'duration' => 90],
            ['name' => 'カット＋カラー', 'price' => 11000, 'duration' => 120],
            ['name' => 'トリートメント', 'price' => 3000, 'duration' => 30],
            ['name' => 'パーマ', 'price' => 8000, 'duration' => 120],
            ['name' => '縮毛矯正', 'price' => 11000, 'duration' => 120],
        ]);
    }
}
