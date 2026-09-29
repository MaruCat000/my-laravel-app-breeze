# 画面遷移フロー

`routes/web.php` / `routes/auth.php` に定義されている、実際に読み込まれているルートのみを記載しています。
（`routes/sampleweb.php` は `bootstrap/app.php` から読み込まれていないため対象外）

```mermaid
flowchart TD
    Start([ブラウザアクセス]) --> Root["GET /<br>index.blade（画面一覧）"]

    Root -->|ログイン画面ボタン| LoginGet["GET /login<br>AuthenticatedSessionController@create"]
    Root -->|ユーザー追加画面ボタン| UserCreate["GET /users/create<br>UserController@create"]

    LoginGet --> LoginView["auth/login.blade"]
    LoginView -->|POST /login| LoginPost["AuthenticatedSessionController@store<br>LoginRequest::authenticate()"]
    LoginPost -->|認証成功 intended リダイレクト| Dashboard
    LoginPost -->|認証失敗 ValidationException| LoginView

    UserCreate --> UserCreateView["users/create.blade<br>fname / lname / email / psw"]
    UserCreateView -->|TOPへ戻る| Root
    UserCreateView -->|POST /users/store| UserStore["UserController@store<br>validate → new User → save<br>Auth::login()"]
    UserStore --> DashboardView["dashboard.blade を直接 return<br>※ redirect ではないため URL は /users/store のまま"]

    subgraph AUTH["ログイン後（middleware: auth, verified）"]
        Dashboard["GET /dashboard<br>TweetController@index<br>Tweet::with user で一覧取得<br>dashboard.blade"]
        TweetCreate["GET /tweets/create<br>TweetController@create<br>tweet/create.blade"]
        TweetStore["POST /tweets<br>TweetController@store"]
        TweetShow["GET /tweets/{tweet}<br>TweetController@show<br>tweet/show.blade（ツイート＋リプライ一覧）"]
        ReplyCreate["GET /replys/create/{tweet}<br>ReplyController@create<br>reply/create.blade"]
        ReplyStore["POST /replys/{tweet}<br>ReplyController@store"]
        Profile["GET /profile<br>ProfileController@edit<br>profile/edit.blade"]
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
    Dashboard -->|ナビのドロップダウン| Logout
    Logout -->|/ へリダイレクト| Root

    %% GET /users はどの画面からもリンクされておらず、URL 直打ちでのみ到達可能
    %% Breeze 標準の GET /register は users テーブルに name カラムがないため動作しない
```

## 補足

- ユーザー登録は Breeze の `/register` ではなく、`UserController` 側（`/users/create` → `/users/store`）を使用しています。Breeze の `RegisteredUserController@store` は `name` カラム前提のままで、本アプリの `users` テーブル（`first_name` / `last_name`）では動作しません。
- `/dashboard` が `TweetController@index`（ツイート一覧）を兼ねています。
- `verified` ミドルウェアは付与していますが、`User` モデルが `MustVerifyEmail` を実装していないため実質的に素通りします。
- `GET /users`（ユーザー一覧）はルートとしては存在しますが、どの画面からもリンクされていません。
