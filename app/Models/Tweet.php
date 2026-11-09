<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\TweetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tweet extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'tweet',
    ];

    public function user()
        {
            return $this->belongsTo(User::class);
        }

}
    