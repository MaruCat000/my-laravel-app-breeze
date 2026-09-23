<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;   // showメソッドでTweetとReplyが必要なのでUSEしておく
use App\Models\Reply; //
use Illuminate\Support\Facades\Auth;    // 認証用

class ReplyController extends Controller
{
    // リプライ一覧表示
    public function reply(){

    }

    // リプライ書き込み処理
    // 20260804 ここの受け取り変数でエラーだと思う
    // Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
    // The POST method is not supported for route replys/create. Supported methods: GET, HEAD.
    // 上記のエラー解消。こんどはtweet_idの受け渡しがうまくいってない。
    // 20260826 ツイートIDの受け渡しはhiddenで行う
    public function store(Request $request,Tweet $tweet){
        // Request $request)

        // バリデーション処理いれる
        $request->validate([
            // {{ old('text') }}を保持したいため名前を付けたことが影響するので変更
            //'reply' => 'required',
            'text' => 'required',

        ]);
        
        // まずリプライクラスのインスタンスを作る
        $replys = new Reply();
        // ツイートIDを引数から取得していれる
        $replys->tweet_id = $tweet->id;
        // ユーザーIDをAuthからとっていれる
        $replys->user_id = Auth::user()->id;
        // requestで取得したtextをいれる
        $replys->reply = $request->text;

        $replys->save();

        // リプライ一覧にリダイレクト
        // 20260805 ここでもツイートIDを保持しておきたいので渡しておく 
        //return redirect()->route('tweet.show');
        return redirect()->route('tweet.show',$tweet);

    }

    // リプライ書き込み画面表示
    public function create(Tweet $tweet){
        // 20260805 ここで画面遷移する時にツイートIDも渡してあげる必要がある
        // 渡してないからエラー
        // Undefined variable $tweet_id
        // 20260826 ツイートIDはhiddenで渡すことにする
        // 20260826 Route Model Bindingを使って簡略できる
        return view('reply/create',compact('tweet'));
        //return view('reply.create', ['tweet_id' => $id]);
    }

}
