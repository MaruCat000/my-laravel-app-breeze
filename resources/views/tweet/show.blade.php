<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ url('style.css') }}" type="text/css">
    <title>Document</title>
</head>
<body>
    <!-- こんにちは、{{ session('user_name') }}さん -->
    こんにちは、{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}さん

<!-- 20260729 以下をツイート一覧からもってきた。リプライ一覧に書き換えていく -->
<!-- 20260804 この辺りにツイートIDから検索したツイート本文のせたいかも -->
<h1>元のツイート</h1>
<!-- dashbordのonclickでroute('tweets.show', $tweet->id)の部分からツイートIDを持ってきて検索結果をかえすようにするとか -->
<!--showメソッド内で検索してtweetを返しているのでそれがあれば(if)表示するだけ -->
    @if ($tweet)
    <table>
        <thead>
            <tr>
                <th>ツイートID</th>
                <th>ユーザネーム</th>
                <th>ツイート</th>
            </tr>
        </thead>
        @csrf
<!-- Undefined variable $tweets -->
<!-- tweetsが定義されていないエラー -->
<!-- コントローラー内のshowメソッド内で検索してしまって、変数に格納して持ってきたらいい -->
        <tbody>
            <tr>
                <td>{{ $tweet->id }}</td>
                <!-- このように単純にnameはtableに存在しない。存在するカラム名で一工夫する
                <td>{{ $tweet->user->name }}</td>
                -->
                <td>{{ ($tweet->user->first_name ?? '') . ' ' . ($tweet->user->last_name ?? '') }}</td>
                <td>{!! nl2br(e($tweet->tweet)) !!}</td>
            </tr>
    </tbody>
</table>            
        @else
            <ul>
                <li>No Tweets!<li>
            </ul>
        @endif

<h1>リプライ一覧</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ツイートID</th>
                <th>ユーザネーム</th>
                <th>リプライ</th>
            </tr>
        </thead>
        
<!-- 20260729 ここでエラー　null -->
<!-- ReplyControllerのreplyメソッド呼び出しでOK
と、思ったが、dashboardからonclickで飛んでいて、そこでroute('tweets.show', $tweet->id)としているのを利用したい
-->
        @csrf
        @forelse ($replys as $reply)
            <tr>
                <td>{{ $reply->id }}</td>
                <td>{{ $reply->tweet_id }}</td>
                <!-- このように単純にnameはtableに存在しない。存在するカラム名で一工夫する
                <td>{{ $tweet->user->name }}</td>
                -->
                <td>{{ ($reply->user->first_name ?? '') . ' ' . ($reply->user->last_name ?? '') }}</td>
                <td>{!! nl2br(e($reply->reply)) !!}</td>
            </tr>            
        @empty
            <ul>
                <li>No Replys!<li>
            </ul>
        @endforelse
    </table>
    <!-- この辺りにリプライ投稿ボタン -->
    <form method="GET" action="{{ route('replys.create',['tweet' => $tweet->id]) }}">        
        @csrf
        <input type="submit" value="リプライ投稿" class="button-style">
    </form>
    <br>
    <!-- 20260729 Breezeでできるので以下不要
    <form method="GET">
        @csrf
        <input type="submit" value="ログアウト" class="button-style">
    </form>
-->
</body>
</html>