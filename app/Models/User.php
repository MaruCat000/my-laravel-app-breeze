<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // タイムスタンプの自動更新を無効にする
    // デフォルトで有効になっているがDB構造と違うのであるとエラーする
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    /* 20260630 breezeを使うためにここをmigrationとseederにあわせる
       protected $fillable = [
        'name',
        'email',
        'password',

 */
        protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            // 20261007 ここを書き換える：hash256にあわせる
            // 20261007 ここが問題。二重ハッシュになっているので、コメントアウトする
            // 20261009 Seederを直したので、コメントアウトは戻す
            'password' => 'hashed',
        ];
    }
}
