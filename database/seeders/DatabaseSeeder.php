<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database
     */
    public function run(){
        // テーブルはマイグレーションで作成済みの前提で、ツイート・リプライのデータを投入する
        $path = database_path('sql/twitter_database_inserts.sql');
        DB::unprepared(file_get_contents($path));

        // usersテーブルは bcrypt でハッシュ化したパスワードを入れるため、SQLファイルではなくここで投入する

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
}
