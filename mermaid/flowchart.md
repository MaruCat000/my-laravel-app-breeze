# 画面遷移フロー

`routes/web.php` / `routes/auth.php` に定義されている、実際に読み込まれているルートのみを記載しています。
（`routes/sampleweb.php` は `bootstrap/app.php` から読み込まれていないため対象外）

```mermaid
flowchart TD
    Start([ブラウザアクセス]) --> Root["GET /<br>index.blade（画面一覧）"]
    Direct([URL を直接入力]) --> RegisterGet["GET /register<br>RegisteredUserController@create"]

    Root -->|ログイン画面ボタン| LoginGet["GET /login<br>AuthenticatedSessionController@create"]
    Root -->|ユーザー追加画面ボタン| UserCreate["GET /users/create<br>UserController@create"]

    LoginGet --> LoginView["auth/login.blade"]
    LoginView -->|POST /login| LoginPost["AuthenticatedSessionController@store<br>LoginRequest::authenticate()"]
    LoginPost -->|認証成功 intended リダイレクト| Dashboard
    LoginPost -->|認証失敗 ValidationException| LoginView

    RegisterGet --> RegisterView["auth/register.blade<br>first_name / last_name / email / password"]
    RegisterView -->|POST /register| RegisterPost["RegisteredUserController@store<br>validate → User::create → Auth::login()"]
    RegisterPost -->|dashboard へリダイレクト| Dashboard

    UserCreate --> UserCreateView["users/create.blade<br>fname / lname / email / psw"]
    UserCreateView -->|TOPへ戻る| Root
    UserCreateView -->|POST /users/store| UserStore["UserController@store<br>※未修正：password が null になり<br>NOT NULL 制約違反で失敗する"]

    subgraph AUTH["ログイン後（middleware: auth, verified）"]
        Dashboard["GET /dashboard<br>TweetController@index<br>Tweet::with user で一覧取得<br>dashboard.blade"]
        TweetCreate["GET /tweets/create<br>TweetController@create<br>tweet/create.blade"]
        TweetStore["POST /tweets<br>TweetController@store"]
        TweetShow["GET /tweets/{tweet}<br>TweetController@show<br>tweet/show.blade（ツイート＋リプライ一覧）"]
        ReplyCreate["GET /replys/create/{tweet}<br>ReplyController@create<br>reply/create.blade"]
        ReplyStore["POST /replys/{tweet}<br>ReplyController@store"]
        Profile["GET /profile<br>ProfileController@edit<br>profile/edit.blade"]
        PasswordUpdate["PUT /password<br>PasswordController@update"]
        Users["GET /users<br>UserController@index<br>users/index.blade"]
        Logout["POST /logout<br>AuthenticatedSessionController@destroy"]
    end

    Dashboard -->|ツイート投稿ボタン| TweetCreate
    TweetCreate -->|投稿| TweetStore
    TweetStore -->|dashboard へリダイレクト| Dashboard

    Dashboard -->|一覧の行を onclick| TweetShow
    TweetShow -->|リプライ投稿ボタン| ReplyCreate
    ReplyCreate -->|投稿| ReplyStore
    ReplyStore -->|tweets.show へリダイレクト| TweetShow

    Dashboard -->|ナビのドロップダウン| Profile
    Profile -->|パスワード変更フォーム| PasswordUpdate
    PasswordUpdate -->|profile へ戻る| Profile
    Dashboard -->|ナビのドロップダウン| Logout
    Logout -->|/ へリダイレクト| Root

    %% GET /register と GET /users はどの画面からもリンクされておらず、URL 直打ちでのみ到達可能
```

## 補足

- **ユーザー登録の経路は2系統あります。** 動作するのは Breeze の `/register` です。TOP ページの「ユーザー追加画面」ボタンは `UserController` 側（`/users/create` → `/users/store`）に向いていますが、`UserController@store` が未修正のため登録が完了しません。
- `/register` はどの画面からもリンクされていないため、URL を直接開く必要があります（`samplewelcome.blade.php` にリンクがありますが、このビューはルーティングされていません）。
- `/dashboard` が `TweetController@index`（ツイート一覧）を兼ねています。
- `verified` ミドルウェアは付与していますが、`User` モデルが `MustVerifyEmail` を実装していないため実質的に素通りします。
- `GET /users`（ユーザー一覧）はルートとしては存在しますが、どの画面からもリンクされていません。
- パスワードリセット（`/forgot-password` → `/reset-password/{token}`）は `password_reset_tokens` テーブルがないため動作せず、この図からは省いています。
