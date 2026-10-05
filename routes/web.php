<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// UserControllerをuseしておく
use App\Http\Controllers\UserController;
// TweetControllerをuseしておく
use App\Http\Controllers\TweetController;
// ReplyControllerをuseしておく
use App\Http\Controllers\ReplyController;
// BreezeのRegisteredUserControllerを使用する
use App\Http\Controllers\Auth\RegisteredUserController;


Route::get('/', function () {
    return view('index');
});


// ログイン必須のグループ（auth & verified）
// この部分ミドルウェア
// 参考URL：https://techinit.co.jp/laravel-routing/
Route::middleware(['auth', 'verified'])->group(function () {

    // authをつかったまま、ここにルーティングを追加していく？
    // 例えばusersのindexとかログイン後の処理

    // --- ダッシュボード・ツイート関連 ---
    Route::get('/dashboard', [TweetController::class, 'index'])->name('dashboard');
    // 20260722 この部分を考えて書き換えていく。いきなりstoreではなくtweet/create.blade.phpに遷移したい
    // 違うな。まずGETで遷移する、が必要
    // TweetControllerにcreateメソッドがない
    Route::get('/tweets/create', [TweetController::class, 'create'])->name('tweets.create');
    Route::post('/tweets', [TweetController::class, 'store'])->name('tweets.store');

    // ツイート詳細画面(リプライ一覧)（{tweet} に tweet の id が入る）
    // 20260730 ツイート一覧(dashbord)から、onclickで遷移する
    Route::get('/tweets/{tweet}', [TweetController::class, 'show'])->name('tweets.show');

    // リプライ投稿画面表示
    // 一工夫。ツイートIDを渡しておく
    // 20260826 Route Model Bindingを使うとよさそう
    // URLに含まれるIDなどのパラメータをもとに、該当するデータベースのレコード（モデルオブジェクト）を自動で検索・取得してコントローラーに渡してくれる機能
    // コントローラーでTweet::findOrFail($id) のような検索コードを書かずに済む
    Route::get('/replys/create/{tweet}',[ReplyController::class,'create'])->name('replys.create');
    // リプライ投稿処理
    // ツイートIDとテキスト内容を引数で渡したい
    // {id}だとユーザーIDが渡りそう
    // 20260805 ここでエラーが起きていた
    // わざわざtextを明言して渡す必要がない
    // HTTPレスポンスで自動的にわたされるから
    //Route::post('/replys/{id}/{text}',[ReplyController::class,'store'])->name('replys.store');
    Route::post('/replys/{tweet}',[ReplyController::class,'store'])->name('replys.store');

    // --- プロフィール関連 ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- ユーザー管理関連 ---
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    


    // 20260729 ルーティング部分をまとめて書けないか
    // 例えば以下のような
    // index以外はまとめてTweetControllerに
    // まとめると必要ないルーティングも発生するからいらないかも
    //Route::resource('tweets',TweetController::class)->except(['index']);
});


    // 新規ユーザー登録画面へのルーティング（RegisteredUserControllerを使う場合） 
    //Route::get('/users/create', [RegisteredUserController::class, 'create'])->name('users.create');
    // 20260826 違う。RegisteredUserControllerはユーザー編集のみ。新規追加は別。
    Route::get('/users/create',[UserController::class,'create'])->name('users.create');
    Route::post('/users/store',[UserController::class,'store'])->name('users.store');
    // 20260916 ユーザー追加後のダッシュボードへの遷移：ミドルウェアのAuthのそと
    

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');



// この部分ミドルウェア
// 参考URL：https://techinit.co.jp/laravel-routing/
//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // authをつかったまま、ここにルーティングを追加していく？
    // 例えばusersのindexとかログイン後の処理
//    Route::get('/users',[UserController::class,'index'])->name('users.index');
    // route('users.login')とroute('users.create')を書いていく
    // 以下の書き方だと、Breezeではなくusersのviewに遷移してしまう。画面一覧からのRoute指定だけでＯＫ
    //Route::get('/users/login',[UserController::class,'login'])->name('users.login');
    // ログイン処理のルーティングが必要。UserControllerのauthenticateメソッドへ遷移
    //Route::post('/login', [UserController::class,'authenticate'])->name('login.authenticate');

    // ここでログインにとんでしまっている
    // createかstoreか名前を統一すること！混乱している
    // ここじゃない！Auth使わないから→使う
    //Route::get('/users/create',[UserController::class,'craete'])->name('users.create');
    // AuthのRegisterを使ってユーザー追加する
    // ここのルーティングじゃなくて、indexのページからのactionでうまく遷移しているような気がする
//    Route::get('/users/store',[RegisteredUserController::class,'create'])->name('users.store');

//});

// これで画面遷移は可能だけど、使うのはAuthのRegusterでは 
//Route::get('/users/store',[UserController::class,'create'])->name('users.store');

require __DIR__.'/auth.php';
