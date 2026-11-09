<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;   // showメソッドでTweetとReplyが必要なのでUSEしておく
use App\Models\Reply; //
use Illuminate\Support\Facades\Auth;    // 認証用

class ReplyController extends Controller
{

    public function reply(){

    }

    public function store(Request $request,Tweet $tweet){

        $request->validate([
            'text' => 'required',

        ]);
        
        $replys = new Reply();
        $replys->tweet_id = $tweet->id;
        $replys->user_id = Auth::user()->id;
        $replys->reply = $request->text;

        $replys->save();

        return redirect()->route('tweet.show',$tweet);

    }

    public function create(Tweet $tweet){
        return view('reply/create',compact('tweet'));
    }

}
