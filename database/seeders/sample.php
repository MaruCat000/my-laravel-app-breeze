<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 他の Seeder クラスを登録
        $this->call(ItemTableSeeder::class);
    }
}

