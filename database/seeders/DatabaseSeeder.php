<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // スタッフ、メニュー、そしてユーザー情報をDBへ流す
        $this->call([
            UserSeeder::class,
            StaffSeeder::class,
            MenuSeeder::class,
            ReservationSeeder::class 
        ]);
    }
}
