<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <!--<meta name="viewport" content="width=device-width,inital-scale=1.0">-->
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ url('style.css') }}" type="text/css">
    </head>
    <body>
        <h1>画面一覧</h1>
        <!-- GETではなくてPOSTで書いていたからエラーしていただけ-->
        <form method="GET" action="{{ route('login') }}">
            @csrf
            <input type="submit" value="ログイン画面" class="button-style">
        </form>
        <br>
        <form method="GET" action="{{ route('users.create') }}">
            @csrf
            <input type="submit" value="ユーザー追加画面" class="button-style">
        </form>
    </body>
</html>