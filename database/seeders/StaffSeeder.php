<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // スタッフ情報
        DB::table('staffs')->insert([
            ['name' => 'Hikaru', 'description' => '骨格の合わせた似合わせカットが得意です！モテ髪はお任せください。'],
            ['name' => 'Yuki', 'description' => 'カラーのスペシャリスト。外国人風カラーが得意です'],
            ['name' => 'Ken', 'description' => 'メンズカット・パーマならお任せください。'],
        ]);
    }
}
