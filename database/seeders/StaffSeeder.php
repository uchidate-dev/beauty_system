<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        // 既存のデータを一旦クリア（二重登録防止：任意ですが安全です）
        // DB::table('staffs')->truncate(); 

        // 1. まずは「指名なし」を固定IDで入れる
        DB::table('staffs')->updateOrInsert(
            ['id' => 999],
            ['name' => '指名なし', 'description' => '担当の希望がない場合はこちらを選択してください。']
        );

        // 2. 他のスタッフ（IDは自動採番）
        // ※ IDが被らないように、もしエラーが出たら手動で消して実行してくださいね！
        DB::table('staffs')->insert([
            ['name' => 'Hikaru', 'description' => '骨格の合わせた似合わせカットが得意です！モテ髪はお任せください。'],
            ['name' => 'Yuki', 'description' => 'カラーのスペシャリスト。外国人風カラーが得意です'],
            ['name' => 'Ken', 'description' => 'メンズカット・パーマならお任せください。'],
        ]);
    }
}
