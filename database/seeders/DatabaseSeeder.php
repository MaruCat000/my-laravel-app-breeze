<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // この1行がなかったのでDBどこ？と捜しにいっていた
use Illuminate\Support\Facades\Hash; // Hashファサードを使用するため

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database
     */

    // ここを考える。このままの形だとmigration使ってないような
    // なんとなくわかった：マイグレーションでDBの箱をつくるので、まずsetup.sqlいらないね？
    public function run(){
        //$path = 'database/sql/twitter_database_setup.sql';
        //DB::unprepared(file_getcontents($path));
        //$path = 'database/sql/twitter_databese_insert.sql';
        $path = database_path('sql/twitter_database_inserts.sql');
        DB::unprepared(file_get_contents($path));


        // 20260707 Bcrypt適用のため以下考えて書き換えた
        // ↑でRunしているINSERT文の中のusersテーブルについてはコメントアウト済み

        // 1. 先にLaravel側でBcryptハッシュ化したパスワード文字列を作っておく
        $hashedPassword = Hash::make('password123');


        // 2. 生SQLのINSERT文をそのまま実行（パスワード部分だけ変数を入れる）
        // Insert data into users table with email and hashed password using Bcrypt
        DB::statement("
            INSERT INTO users (first_name, last_name, email, password) VALUES
            ('John', 'Doe', 'john@john.com', '{$hashedPassword}'),
            ('Jane', 'Smith', 'jane@jane.com', '{$hashedPassword}'),
            ('Alice', 'Johnson', 'alice@alice.com', '{$hashedPassword}'),
            ('Bob', 'Williams', 'bob@bob.com', '{$hashedPassword}'),
            ('Charlie', 'Brown', 'brown@brown.com', '{$hashedPassword}'),
            ('David', 'Miller', 'david@david.com', '{$hashedPassword}'),
            ('Eve', 'Davis', 'eve@eve.com', '{$hashedPassword}'),
            ('Frank', 'Wilson', 'frank@frank.com', '{$hashedPassword}'),
            ('Grace', 'Moore', 'moore@moore.com', '{$hashedPassword}'),
            ('Hannah', 'Taylor', 'taylor@taylor.com', '{$hashedPassword}');
            
        ");
    }

    // 20260525 testデータをいれてみる
    /*
    User::create([
        'name' => 'テストユーザー',
        'email' => 'test@example.com',
        'password' => Hash::make('password123'), // Laravel公式の暗号化
    ]);
    */

}