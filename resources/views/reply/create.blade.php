<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ url('style.css') }}" type="text/css">
    <title>リプライ投稿画面</title>
</head>
<body>
    <!-- ここにフォームを書いていく -->
    <h1>リプライ投稿画面</h1>
    @error('text')
        <p class="error">{{ $message }}</p>
    @enderror
<!-- 20260826 この辺りに、リプライ元のツイート本文をもってきておいたほうが分かりやすい -->

    <!-- 20260804 ここで解消した疑問。textが何故ルーティングに含めないでいいのか
 text ＝ 画面の入力欄（<textarea name="text">）があるから、ブラウザが自動で送信してくれる（$request で受け取る）
    ['id' => $tweet_id]) }}">-->
<!-- 20260805 三宅さんからの回答。フォームの内容は$requestで扱われているとのこと。GETのようにURLで送ってない。あくまでPOST
 Illuminate\Http\Requestで実現している。Laravel フレームワークの機能の一つ -->
    <form method="POST" action="{{ route('replys.store',$tweet) }}">
        @csrf     
        @csrf     
        リプライ:
        <textarea name="text">{{ old('text') }}</textarea>
        <br>
        <input type="submit" name="search" value="投稿" class="button-style">
    </form>
</body>
</html>