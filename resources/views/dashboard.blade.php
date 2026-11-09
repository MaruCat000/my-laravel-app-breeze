<link rel="stylesheet" href="{{ url('style.css') }}" type="text/css">
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
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

        @forelse ($tweets as $tweet)
            <tr onclick="location.href='{{ route('tweets.show', $tweet->id) }}'" class="cursor-pointer">
                <td>{{ $tweet->id }}</td>
                <td>{{ ($tweet->user->first_name ?? '') . ' ' . ($tweet->user->last_name ?? '') }}</td>
                <td>{{ $tweet->tweet }}</td>
            </tr>            
        @empty
            <ul>
                <li>No Tweets!<li>
            </ul>
        @endforelse

    </table>
     @csrf
    <form method="GET" action="{{ route('tweets.create') }}">
        <input type="submit" value="ツイート投稿" class="button-style">
    </form>
    <br>
</x-app-layout>
