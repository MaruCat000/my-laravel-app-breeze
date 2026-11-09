<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ url('style.css') }}" type="text/css">
    <title>Document</title>
</head>
<body>
    こんにちは、{{ session('user_name') }}さん
    <h1>投稿一覧</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ユーザネーム</th>
                <th>ツイート</th>
            </tr>
        </thead>
        
        @csrf
        @forelse ($tweets as $tweet)
            <tr>
                <td>{{ $tweet->id }}</td>
                <td>{{ ($tweet->user->first_name ?? '') . ' ' . ($tweet->user->last_name ?? '') }}</td>
                <td>{!! nl2br(e($tweet->tweet)) !!}</td>
            </tr>            
        @empty
            <ul>
                <li>No Tweets!<li>
            </ul>
        @endforelse
    </table>
    <form method="GET">
        @csrf
        <input type="submit" value="ツイート投稿" class="button-style">
    </form>
    <br>
    <form method="GET">
        @csrf
        <input type="submit" value="ログアウト" class="button-style">
    </form>
</body>
</html>