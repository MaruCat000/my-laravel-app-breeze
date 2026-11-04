# 画面遷移フロー

`routes/web.php` / `routes/auth.php` に定義されている、実際に読み込まれているルートのみを記載しています。
（`routes/sampleweb.php` は `bootstrap/app.php` から読み込まれていないため対象外）

```mermaid
flowchart TD
    Start([ブラウザアクセス]) --> Root["GET /<br>index.blade（画面一覧）"]

    Root -->|ログイン画面ボタン| LoginGet["GET /login<br>AuthenticatedSessionController@create"]
    Root -->|ユーザー追加画面ボタン| RegisterGet["GET /register<br>RegisteredUserController@create"]

    LoginGet --> LoginView["auth/login.blade"]
    LoginView -->|POST /login| LoginPost["AuthenticatedSessionController@store<br>LoginRequest::authenticate()"]
    LoginPost -->|認証成功 intended リダイレクト| Dashboard
    LoginPost -->|認証失敗 ValidationException| LoginView

    RegisterGet --> RegisterView["auth/register.blade<br>first_name / last_name / email / password"]
    RegisterView -->|POST /register| RegisterPost["RegisteredUserController@store<br>validate → User::create → Auth::login()"]
    RegisterPost -->|dashboard へリダイレクト| Dashboard

    subgraph AUTH["ログイン後（middleware: auth, verified）"]
        Dashboard["GET /dashboard<br>TweetController@index<br>Tweet::with user で一覧取得<br>dashboard.blade"]
        TweetCreate["GET /tweets/create<br>TweetController@create<br>tweet/create.blade"]
        TweetStore["POST /tweets<br>TweetController@store"]
        TweetShow["GET /tweets/{tweet}<br>TweetController@show<br>tweet/show.blade（ツイート＋リプライ一覧）"]
        ReplyCreate["GET /replys/create/{tweet}<br>ReplyController@create<br>reply/create.blade"]
        ReplyStore["POST /replys/{tweet}<br>ReplyController@store"]
        Profile["GET /profile<br>ProfileController@edit<br>profile/edit.blade"]
        PasswordUpdate["PUT /password<br>PasswordController@update"]
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
```

## 補足

- ユーザー登録は Breeze の `/register` に一本化しています。TOP ページの「ユーザー追加画面」ボタンから遷移します（独自の `UserController` は削除済み）。
- `/dashboard` が `TweetController@index`（ツイート一覧）を兼ねています。
- `verified` ミドルウェアは付与していますが、`User` モデルが `MustVerifyEmail` を実装していないため実質的に素通りします。
- パスワードリセット（`/forgot-password` → `/reset-password/{token}`）は `password_reset_tokens` テーブルがないため動作せず、この図からは省いています。
