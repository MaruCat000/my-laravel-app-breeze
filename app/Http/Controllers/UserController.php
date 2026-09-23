<?php

/* 20260423 start デフォルトコメントアウト
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
}
20260423 end
*/

// 20260604 ここの名前不足してない？→してなかった。追記するとエラーになる。extendsでController使ってるからOKみたい
namespace App\Http\Controllers;
//namespace App\Http\Controllers\UserController;

use App\Models\User;    // ユーザーモデルを使用
use Illuminate\Http\Request; // ファイルの上部に必要
use Illuminate\Support\Facades\Auth; // 認証用
// 20260911 ここが原因かとおもってuseしたけどエラーする
//use App\Http\Controllers\Valitator; //ヴァリデーションチェック用
// 20260916 検索して書き直し
// https://qiita.com/gone0021/items/c613ef7e006b6f5d47ce
use Illuminate\Support\Facades\Validator;


// extends がエラー
class UserController extends Controller
{
    public function index(){
        // データベースから全ユーザー情報を取得
        // Model使ってないから……
        // useでApp\Models\Userしているので簡略して書ける
        //$users = App\Models\User::all();
        $users = User::all();

        // ビューにユーザー情報を渡して表示
        return view('users.index',['users' => $users]);
    }

    // 20260526 PHPからLaravelへの対応で、
    // Laravelでは画面をただ表示するGETと
    // ボタンを押した時にPOSTで関数を呼ぶと
    // 分けて書く

    public function login(){
        // login.blade.phpという画面をただ表示する
        return view('users.login');
    }


    // この部分ログインのPOST版にする（引数を受け取って処理する）
    // データベース接続 ($pdo) は不要
    // データベース接続 ($pdo) は不要
    // session_start() は不要
    // User::all() の活用
    // メールアドレスとパスワードが一致したらログインする処理
    // 20260826 Breezeですでにあるのauthenticateのメソッドは不要
    public function authenticate(Request $request){
    //public function authenticate($mail,$psw){

    // サンプル
    // UserController.php

        // 1. GETアクセスの時は、単にログイン画面を表示するだけ
        /* 20260526 getで画面表示のみは別に切り分けたので不要になったのでコメントアウト 
        if ($request->isMethod('get')) {
            return view('users.login');
        }
        */

// 20260701 この部分、もっとスッキリ書けるかも
// Laravelは「コントローラーを汚さずに、入り口の検問所で悪いリクエストを弾く」というフレームワークだから
// 1. 入力チェックと型変換を追い出す：Form Request
// 2. データベース操作を追い出す：Query Builder / Scope

// コントローラーに届いた時点ですでにデータは綺麗なはず

// 20260701 三宅さんの回答：この点は現場によってまちまちです。。。
// この講座内では、バリデーション、DB操作以外はControllerにしちゃって大丈夫です！

        // 2. ここから下は POST（ログインボタンが押された時）の処理
        // 入力チェック（バリデーション）
        // 20260609 ここが気になる。エラーメッセージがLaravelのデフォルトのThe psw field is required.みたいになる
        // https://qiita.com/2024_Hello_World/items/991445da967eba1839c6 が参考になるロケーションなどの設定
        $request->validate([
            'mail' => 'required|email',
            'psw'  => 'required',
        ]);

        $usermail = $request->input('mail');
        $inputPassword = hash("sha256", $request->input('psw'));

        // ポイント：PDOを使わず Userモデル（Eloquent）で検索
        // Laravelが自動でプリペアドステートメントにしてくれるので安全
        $user = User::where('email', $usermail)
                    ->where('password', $inputPassword)
                    ->first();

        if ($user) {
            // 成功：Laravelのセッション機能を使う（session_startは不要）
            session([
                'user_id' => $user->id,
                'user_name' => ($user->first_name ?? '') . " " . ($user->last_name ?? '')
            ]);

            // ここhomeじゃなくてindexじゃない？
            //return redirect('/home'); // header() の代わりに redirect()
            // 20260608 ここが効いていて、ユーザー一覧表示される
            //return redirect()->route('users.index');
            // 20260609 ここにAuthを足してログインを持ちまわる？
            // ↑のsession部分は？
            return redirect()->route('tweet.index');
        }

        // 失敗：エラーメッセージ付きで元のページに戻る
        return back()->withErrors(['auth' => 'メールアドレスまたはパスワードが違います。']);
    }

    // 20260526 updateとeditの違いを調べた。
    // editは編集フォームの表示のみのGET
    // updateは更新処理のPOST

    // ユーザー編集画面表示
    /*public function edit(){
        // update.blade.phpという画面をただ表示する
        return view('users.update');
    }
    */

    // 20260828 Breezeですでにあるので、このupdateメソッドも不要
    // ユーザー編集処理
    public function update(Request $request){
        // 実際の編集処理updateの本体
    

    }

    // ユーザー追加処理
    // 20260626 indexは、ユーザー一覧表示ですでに使われている
    // createでまず画面を見せる
    public function create(){
        return view('users.create');
    }


    // 20260911 ヴァリデートがエラー
    // authenticateメソッドがうまく動いていたので、それを参考に書き直す
    // 実際のユーザー登録処理(POSTで飛んできたとき)
    public function store(Request $request){

        // nullではないとするヴァリデーション
        // ここがないとエラー画面が見えてしまう
        // 20260616 ここもヴァリデートの項目として指定する必要あり

        // 20260911 まず、ヴァリデーションチェックする
/*        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'mail' => 'required|email',
            'psw'  => 'required',
        ]);
*/
        // ユーザー追加処理



        // 20260916 Validatorのuniqueについて 
        // https://qiita.com/gone0021/items/c613ef7e006b6f5d47ce


        // 20260828 メールアドレスのみ重複チェックしたい
        // 【Laravel】登録・更新時のDBデータ重複チェック方法（Validator＋unique、DB::table＋whereRaw）
        // https://dad-union.com/laravel-db-duplicate-check-validator-unique
        // 20260911 おそらくこの部分のvalidatorがエラー
        /*$validator = Valitator::make($request->all(),[
            'email' => 'required|unique:users,email'
        ]);
*/


        // 20260916 ここのemailのuniqueが効いているが、エラーメッセージがでない
        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|unique:users',
            'psw' => 'required',
        ]);



        $user = new User();
        // 20260626 ここをテーブルのカラムに合わせて書いていく
        $user->first_name = $request->fname;
        $user->last_name = $request->lname;
        $user->email = $request->email;
        
        // pswに関して、ハッシュ化して保存する
        $inputPassword = hash("sha256", $request->input('psw'));

        $user->password = $inputPassword;
        $user->save();

        // ユーザー一覧に飛んだらよさそう
        // 20260916 ここでエラー：ダッシュボードにとばす
        //return redirect()->route('tweet.index');
        // 20260916 ここでエラー:Authに情報がないままダッシュボードにとんでいるから

        // Authに情報を入れておく
        Auth::login($user);

        // 20260916 ここの遷移先がエラーする
        return view('dashboard');
        //return route('login');


    }

}
