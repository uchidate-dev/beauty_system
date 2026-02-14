<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // スタッフ、メニュー、そしてユーザー情報をDBへ流す
        $this->call([
            StaffSeeder::class,
            MenuSeeder::class,
            UserSeeder::class, 
        ]);
    }
}
