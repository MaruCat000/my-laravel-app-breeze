<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ url('style.css') }}" type="text/css">
    <title>ツイート投稿画面</title>
</head>
<body>
    <h1>ツイート投稿画面</h1>
    @error('text')
        <p class="error">{{ $message }}</p>
    @enderror
    <form method="POST" action="{{ route('tweets.store') }}">
        @csrf     
        ツイート:
        <textarea name="text">{{ old('text') }}</textarea>
        <br>
        <input type="submit" name="search" value="投稿" class="button-style">
    </form>

</body>
</html>
