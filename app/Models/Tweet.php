<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;   //デフォルトでできたもの
use Database\Factories\TweetFactory;    // ここはいらないかも
use Illuminate\Database\Eloquent\Factories\HasFactory;  // ここもいらないかも
//use Illuminate\Foundation\Auth\Tweet as Authenticatable;  // Uwerを参考にしたが、Userは認証が必要。Tweetはただの表示で認証不要
//use Illuminate\Notifications\Notifiable;


//class Tweet extends Authenticatable
class Tweet extends Model
{
    //
    use HasFactory;//Notifiable;

    // タイムスタンプの自動更新を無効にする
    // デフォルトで有効になっているがDB構造と違うのであるとエラーする
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'tweet',
    ];

    // ツイートテーブルのユーザーIDからユーザーテーブルで名前を検索できるようにここを足しておく
    public function user()
        {
            // belongsTo（〜に所属する）を使うことで、user_id を使って自動でUserテーブルを探してくれる
            return $this->belongsTo(User::class);
        }

    // パスワードなどhiddenで持たせておく？

    protected function casts(): array
    {
        return [

        ];

    }
}
    