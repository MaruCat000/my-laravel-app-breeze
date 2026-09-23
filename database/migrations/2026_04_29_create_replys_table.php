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
        Schema::create('replys', function (Blueprint $table) {
            $table->id();
            //$table->tweet_id();
            //  修正後（よくある型指定）
            $table->foreignId('tweet_id')->constrained('tweets')->onDelete('cascade');
            //$table->user_id();
            //  修正後（よくある型指定）
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('reply');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replys');
    }
};
