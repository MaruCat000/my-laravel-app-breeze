<?php

namespace App\Http\Controllers;

use App\Models\Tweet;   // showメソッドでTweetとReplyが必要なのでUSEしておく
use App\Models\Reply; //
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    // 認証用


class TweetController extends Controller
{

    public function index(){
        // データベースから全ユーザー情報を取得
        // Model使ってないから……
        // useでApp\Models\Userしているので簡略して書ける
        //$users = App\Models\User::all();

        // この部分をツイートと一緒にユーザーデータも持ってくるように書き換える
        //$tweets = Tweet::all();
        $tweets = Tweet::with('user')->get();

        // ビューにユーザー情報を渡して表示
        //return view('Tweet.index',['tweets' => $tweets]);
        // Tweet.indexではなくてdashbordに遷移させる
        return view('dashboard', compact('tweets')); 
    }

    
    // ツイート一覧表示
    /* 20260608 indexでできるので不要
    public function show(){

    }
    */
    
    // ツイート投稿画面表示
    public function create(){
        return view('tweet/create');
    }

    // ツイート投稿処理
    public function store(Request $request){

        // バリデーション処理いれる
        $request->validate([
            // 20260805 ここでエラーtweet指定ではなくtextareaのtextで名前がついているので
            // textをヴァリデーションチェックしないとエラーになって書き込めなかった 
            // ヴァリデーションチェックの後エラーで戻された時に
            // {{ old('text') }}を保持したいため名前を付けたことが影響
            //'tweet' => 'required',
            'text' => 'required',

        ]);

        // まずツイートクラスのインスタンスを作る
        $tweets = new Tweet();
        // ユーザーIDをAuthからとっていれる
        $tweets->user_id = Auth::user()->id;
        // requestで取得したtextをいれる
        $tweets->tweet = $request->text;

        $tweets->save();

        // ダッシュボード(ツイート一覧)にリダイレクト
        return redirect()->route('dashboard');
    }
    
/* 20260729 リプライ一覧表示、ここでエラー
    public function show(Tweet $tweet){
    // リプライ（リレーションを設定している場合）を一緒に取得してビューへ渡す
    // ※ Replyモデルやリレーションの設定に応じて調整する
    // ツイートのIDからリプライ一覧をとってくる
    $replies = $tweet->replies; 

    return view('tweet.show', compact('tweet', 'replies'));
}
    */

// 20260729 このメソッド部分がエラー
// TweetとReplyのモデルをUSEする必要がある
// 20260730 replyが取れずnullが渡っている
// 20260826 Route Model Bindingを使って簡略できる
    //public function show($id){
    public function show(Tweet $tweet){
        // 該当のツイートを取得
        //20260826 Route Model Bindingを使って簡略できる
        //$tweet = Tweet::findOrFail($id);


        // リプライ一覧を取得（モデルにリレーションを設定していないので、自分で直接 repliesテーブルの中からtweet_id が合致するものを探す）
        // 20260730 こちらを活かすと
        // $replys = Reply::where('tweet_id', $id)->get();
        // の部分でクエリエラーする
        // Illuminate\Database\QueryException
        // SQLSTATE[42S02]: Base table or view not found: 1146 Table 'twitter.replies' doesn't exist (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: twitter, SQL: select * from `replies` where `tweet_id` = 1)
        $replys = Reply::where('tweet_id', $tweet->id)->get();
        // 20260730 nullエラー予測この部分。リレーションがうまくいってない。↑のやり方にする
        // ↑のやりかたにすると、Replyモデルの中のHasFactoryのuseがエラー
        // function repliesを設定しているとこう書ける
        //$replys = $tweet->replies; // リレーション経由でリプライを取得

        // 20260730 解決：Replyモデルにすると、Laravelがデフォルトでrepliesテーブルを検索してしまう
        // 実際の設定DBのテーブルreplysと名前が違っていた
        // Replyモデル内で、テーブル名の指定をし直して解決


        // ビューに $tweet と $replys の両方を渡す
        // 20260804 ここで渡しているtweetをbladeで取得して表示すればいい

        //20260826 ここでツイート本体を渡しているが、ツイートIDを渡した方がいい？
        // なぜなら、リプライ書き込みの時に必要になるから
        // 調べた。hiddenで持たせたらよさそう
        // Route Model Bindingを使ったらよさそう
        return view('tweet.show', compact('tweet', 'replys'));
    }


    // ツイートとリプライをリレーションしておく
    // リプライ一覧表示のため
    // 20260804 このメソッドは使ってない
    // showメソッドでの呼び出しだが、DBのカラム名replysと混ざってわかりにくい
    public function replies(){
        return $this->hasMany(Reply::class);
    }
}
