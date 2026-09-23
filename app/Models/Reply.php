<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // 202060730 ここをUSEしてなかったのでエラーしていた
// 20260730 このあたりのエラーでは？USEできてない、マイグレーションしなくて作った兼ね合いのエラーのような

class Reply extends Model
{
    //
    use HasFactory;//Notifiable;

    // 20260730 Illuminate\Database\QueryException
    // Laravelの基本設定でReplyモデルだと`replies`テーブルが呼び出されるエラー
    // テーブル名を指定しなおす
    protected $table = 'replys';

    // タイムスタンプの自動更新を無効にする
    // デフォルトで有効になっているがDB構造と違うのであるとエラーする
    public $timestamps = false;

    protected $fillable = [
        'tweet_id',
        'user_id',
        'reply',
    ];

    // リプライテーブルのユーザーIDからユーザーテーブルで名前を検索できるようにここを足しておく
    public function user(){
            // belongsTo（〜に所属する）を使うことで、user_id を使って自動でUserテーブルを探してくれる
            return $this->belongsTo(User::class);     
    }

}
