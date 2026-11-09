<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ url('style.css') }}" type="text/css">
    <title>Document</title>
</head>
<body>
    こんにちは、{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}さん
<h1>元のツイート</h1>
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
        <tbody>
            <tr>
                <td>{{ $tweet->id }}</td>
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
        @csrf
        @forelse ($replys as $reply)
            <tr>
                <td>{{ $reply->id }}</td>
                <td>{{ $reply->tweet_id }}</td>
                <td>{{ ($reply->user->first_name ?? '') . ' ' . ($reply->user->last_name ?? '') }}</td>
                <td>{!! nl2br(e($reply->reply)) !!}</td>
            </tr>            
        @empty
            <ul>
                <li>No Replys!<li>
            </ul>
        @endforelse
    </table>
    <form method="GET" action="{{ route('replys.create',['tweet' => $tweet->id]) }}">        
        @csrf
        <input type="submit" value="リプライ投稿" class="button-style">
    </form>
    <br>
</body>
</html>