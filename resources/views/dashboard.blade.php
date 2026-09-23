<link rel="stylesheet" href="{{ url('style.css') }}" type="text/css">
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
20260708 ここを書き換えていく
<br>
例えば
<br>
ツイート一覧
<br>
リプライ一覧
<br>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ Auth::user()->id }}
                    {{ Auth::user()->first_name }}
                    {{ Auth::user()->last_name }}
                    {{ __(". You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
    こんにちは、{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}さん
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
<!-- 20260708 ここでエラー　Undefined variable $tweets -->
<!-- TweetControllerのindexメソッド呼び出しでOK -->
        @forelse ($tweets as $tweet)
            <tr onclick="location.href='{{ route('tweets.show', $tweet->id) }}'" class="cursor-pointer">
                <td>{{ $tweet->id }}</td>
                <!-- このように単純にnameはtableに存在しない。存在するカラム名で一工夫する
                <td>{{ $tweet->user->name }}</td>
                -->
                <td>{{ ($tweet->user->first_name ?? '') . ' ' . ($tweet->user->last_name ?? '') }}</td>
                <td>{{ $tweet->tweet }}</td>
            </tr>            
        @empty
            <ul>
                <li>No Tweets!<li>
            </ul>
        @endforelse

    </table>
    <!-- この辺りにツイート投稿ボタン -->
     @csrf
    <form method="GET" action="{{ route('tweets.create') }}">
        <input type="submit" value="ツイート投稿" class="button-style">
    </form>
    <br>
    <!-- 20260728 Breezeでできるのでここは不要
    @csrf
    <form method="GET">
        <input type="submit" value="ログアウト" class="button-style">
    </form>
-->
</x-app-layout>
