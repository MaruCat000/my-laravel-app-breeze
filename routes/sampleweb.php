<?php

/*20260423 start デフォルトをコメントアウト
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
20260423 end
*/

// TOPページ
use Illuminate\Support\Facades\Route;

Route::get('/',function(){
    return view('index');

});

// 20260616 このあたりにindexからのルートが必要では？ないから画面遷移がうまくいかないような……
//  違う…userの中のlogin書いてある……



// ユーザーについて

use App\Http\Controllers\UserController; // 上の方にこれが必要
// この書き方だけではうまくいかない
// Route::get('/login', [UserController::class, 'login']);


// GET：ログイン画面を表示する
// 20260604 検証のため一時コメントアウト→ここは不要だった。おそらくサンプルコード
//Route::get('/login', [UserController::class, 'login'])->name('users.login_form');
// 順番を整理・コメントアウトしてloginの下に配置
// POST：ログイン処理を実行する（メソッド名を仮に authenticate とする）
//Route::post('/login', [UserController::class, 'authenticate'])->name('users.login');

// routes/index.php
//use App\Http\Controllers\UserController;
Route::get('/users',[UserController::class,'index'])->name('users.index');
// 1つにまとめる
Route::resource('/users',UserController::class)->except(['index']);

// とりあえずのエラー回避
// これを追記（UserController の login2 メソッドを users.login という名前で登録）
// おそらくここが効いていて、画面遷移するlogin.blade.php
// 20260616 ここが効いていたらAタグでなくてinputで画面遷移しそうに思うけど……
// htmlのGETがPOSTで書かれていただけのエラーだった
Route::get('/login', [UserController::class, 'login'])->name('users.login');
// 20260520 Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
// HTTPアクセスのメソッド（GET/POST）に問題があるエラー。ルーティングを確認し、適切な記述をして解決をする。
// POST：ログイン処理を実行する（メソッド名を仮に authenticate とする）
// Requestがある時に値を渡して処理する
//Route::post('/login/{mail}/{psw}', [UserController::class,'authenticate'])->name('login.authenticate');
// 引数いらない
// 通常のログイン処理（POST送信）では、メールアドレスやパスワードはURLに載せるのではなく、
// フォームの入力値としてリクエストの裏側（Body）で送信するのが一般的。
// URLにパスワードが丸見えになってしまうのはセキュリティ的にも良くない。
Route::post('/login', [UserController::class,'authenticate'])->name('login.authenticate');



// ログインページ
//Route::get('/users/login',[userController::class,'login']);

// それぞれのページのRoute::post();を書いていく？
// 20260526 書かなくてOK：なぜなら、Route::resouceで作られているから
// ユーザー編集
// routes/edit.php
//Route::Post('/users/edit',[UserController::class,'update']);

// ユーザー追加
// routes/add.php
//Route::Post('/users/add',[UserController::class,'store']);
// 20260625 user.indexに遷移している
Route::get('/users/create',[UserController::class,'create'])->name('users.create');


//tweetについて
use App\Http\Controllers\TweetController;
// 記事一覧
Route::get('/tweet',[TweetController::class,'index'])->name('tweet.index');
//Route::get('/tweet',[TweetController::class,'add'])->name('tweet.add');
// 1つにまとめる
Route::resource('/tweet',TweetController::class)->except(['index']);

// 記事投稿
//Route::Post('/tweet/add',[TweetController::class,'store']);

// Replyについて
use App\Http\Controllers\ReplyController;
// リプライ一覧
Route::get('/reply',[ReplyController::class,'index']);
// 1つにまとめる
Route::resource('/reply',ReplyController::class)->except(['index']);

// リプライ追加
//Route::Post('/reply/add',[ReplyController::class,'store']);

// ログアウト
// 画面遷移だけでOK？

// ログアウト処理
// セキュリティ（セッションの破棄）に関わるので、POSTで送信するのがLaravelのセオリー(標準)
Route::post('/logout', [UserController::class, 'logout'])->name('users.logout');