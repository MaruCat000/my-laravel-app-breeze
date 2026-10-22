# シーケンス図

## 新規ユーザー登録

Breeze の `/register` 経由の登録フローです（動作する経路）。

```mermaid
sequenceDiagram
    actor U as ユーザー
    participant B as ブラウザ
    participant C as RegisteredUserController
    participant M as User モデル
    participant DB as DB（users）

    U->>B: GET /register
    B->>C: create()
    C-->>B: auth/register.blade 表示
    U->>B: フォーム送信 POST /register
    B->>C: store(Request)
    C->>C: validate（first_name, last_name, email は unique, password は confirmed）
    alt バリデーションNG
        C-->>B: エラーと old() 付きで入力画面へ戻る
    else バリデーションOK
        C->>M: User::create() に検証済みの $validated を渡す
        M->>M: 'password' => 'hashed' キャストが平文を bcrypt 化
        M->>DB: INSERT
        C->>C: event(new Registered) / Auth::login(user)
        C-->>B: /dashboard へリダイレクト
    end
```

`UserController@store`（`/users/store`）にも登録処理がありますが、パスワードの参照先が誤っていて未修正のため、こちらは図に含めていません。

## ログイン

```mermaid
sequenceDiagram
    actor U as ユーザー
    participant B as ブラウザ
    participant A as AuthenticatedSessionController
    participant L as LoginRequest
    participant DB as DB（users）

    U->>B: GET /login
    B->>A: create()
    A-->>B: auth/login.blade 表示
    U->>B: フォーム送信 POST /login
    B->>A: store(LoginRequest)
    A->>L: authenticate()
    L->>L: ensureIsNotRateLimited()（同一メール＋IPで5回失敗すると Lockout）
    L->>DB: Auth::attempt(email, password) で bcrypt 照合
    alt 認証成功
        L->>L: RateLimiter::clear()
        A->>A: セッションIDを再生成
        A-->>B: intended リダイレクトで /dashboard へ
    else 認証失敗
        L->>L: RateLimiter::hit()
        L-->>B: ValidationException（auth.failed）で /login へ戻る
    end
```

bcrypt はハッシュごとにソルトが異なるため、`WHERE password = ?` のような等価比較では照合できません。
`Auth::attempt()` が内部で `Hash::check()` を呼び、ハッシュに埋め込まれたソルトを使って突き合わせます。

## パスワード変更

```mermaid
sequenceDiagram
    actor U as ユーザー
    participant B as ブラウザ
    participant P as PasswordController
    participant M as User モデル
    participant DB as DB（users）

    U->>B: /profile のパスワード変更フォームを送信 PUT /password
    B->>P: update(Request)
    P->>P: validateWithBag('updatePassword')
    P->>DB: current_password ルールが Hash::check() で現在のパスワードを検証
    alt 検証NG
        P-->>B: updatePassword バッグのエラーを添えて戻る
    else 検証OK
        P->>M: update() に検証済みの $validated を渡す
        M->>M: 'password' => 'hashed' キャストが bcrypt 化
        M->>DB: UPDATE
        P-->>B: status=password-updated を添えて戻る
    end
```

## ログアウト

```mermaid
sequenceDiagram
    actor U as ユーザー
    participant B as ブラウザ
    participant A as AuthenticatedSessionController

    U->>B: ナビのドロップダウンから POST /logout
    B->>A: destroy(Request)
    A->>A: Auth::guard('web') でログアウト
    A->>A: セッションを破棄してトークンを再生成
    A-->>B: / へリダイレクト
```
