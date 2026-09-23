<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            /* 20260525 型を指定しないとエラーする
            $table->first_name();
            $table->last_name();
            $table->email();
            $table->password();
            */

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique(); // メールアドレスは重複しないようにuniqueを付けるのが一般的
            $table->string('password');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
