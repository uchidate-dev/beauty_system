<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // ここでIDを 1, 2, 3 と「明記」するのが最大のコツです！
        DB::table('staffs')->insert([
            [
                'id' => 1, // ←絶対に変えない
                'name' => 'Hikaru',
                'description' => '骨格の合わせた似合わせカットが得意です！モテ髪はお任せください。',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 2, // ←絶対に変えない
                'name' => 'Yuki',
                'description' => 'カラーのスペシャリスト。外国人風カラーが得意です',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 3, // ←絶対に変えない
                'name' => 'Ken',
                'description' => 'メンズカット・パーマならお任せください。',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);
    }
}
