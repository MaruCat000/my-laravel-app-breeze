<?php

namespace App\Http\Controllers;

use App\Models\Tweet; 
use App\Models\Reply; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TweetController extends Controller
{

    public function index(){
        $tweets = Tweet::with('user')->get();

        return view('dashboard', compact('tweets')); 
    }


    public function create(){
        return view('tweet/create');
    }


    public function store(Request $request){
        $request->validate([
            'text' => 'required',
        ]);

        $tweets = new Tweet();
        $tweets->user_id = Auth::user()->id;
        $tweets->tweet = $request->text;

        $tweets->save();

        return redirect()->route('dashboard');
    }
    
    public function show(Tweet $tweet){
        $replys = Reply::where('tweet_id', $tweet->id)->get();
        
        return view('tweet.show', compact('tweet', 'replys'));
    }

    public function replies(){
        return $this->hasMany(Reply::class);
    }
}
