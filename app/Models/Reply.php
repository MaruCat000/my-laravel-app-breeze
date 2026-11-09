<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reply extends Model
{
    use HasFactory;//Notifiable;

    protected $table = 'replys';

    public $timestamps = false;

    protected $fillable = [
        'tweet_id',
        'user_id',
        'reply',
    ];

    public function user(){
        return $this->belongsTo(User::class);     
    }

}
