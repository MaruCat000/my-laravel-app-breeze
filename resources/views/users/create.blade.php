<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,inital-scale=1.0">
        <link rel="stylesheet" href="{{url('style.css')}}" type="text/css">
    </head>
    <body>
        <h1>ユーザー追加画面</h1>
        <br>
<!--        // ・first_name, last_name, email, passwordを登録する
        <br>
        // ・passwordはSHA256でハッシュ化して登録する
        <br>
        <br>

        // まず、DBから編集したいユーザー情報を取得するひつようあり
        // 20260220 三宅さん：ログインしてからの画面なので検索されたあとになっている
        <br>
        // 以下はいらない
        // 「検索」ボタン
        <br>
        // firstname、lastname、e-mailで検索できると便利
        <br>
-->

        @error('lname')
        @enderror

            <form method="POST" action="{{ route('users.store') }}">
            @csrf
            ファーストネーム:
            <input type="text" name="fname" value="{{ old('fname') }}">
            <!-- 20260526 ドットインストールを参考にuserのstore.blade.phpのエラーを取得するところ-->
            @error('fname')
                <p class="error">{{ $message }}</p>
            @enderror
            <br>
            ラストネーム:
            <input type="text" name="lname" value="{{ old('lname') }}">
            <!-- 20260526 ドットインストールを参考にuserのstore.blade.phpのエラーを取得するところ-->
            @error('lname')
                <p class="error">{{ $message }}</p>
            @enderror
            <br>
            e-mail:
            <input type="text" name="email" value="{{ old('email') }}">
            <!-- 20260526 ドットインストールを参考にuserのstore.blade.phpのエラーを取得するところ-->
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
            <br>
            パスワード:
<!-- ここでエラーする：パスワードは空にしておく？ -->
            <input type="password" name="psw">
            <!-- 20260526 ドットインストールを参考にuserのstore.blade.phpのエラーを取得するところ-->
            @error('psw')
                <p class="error">{{ $message }}</p>
            @enderror
            <br>
            <!-- <input type="submit" value="検索"> -->
            <input type="submit" name="registration" value="登録">
        </form>
        <BR>
        <form method="GET" action="{{ '/' }}">
            @csrf
            <input type="submit" value="トップページへ" class="button-style">
        </form>
    </body>
</html>