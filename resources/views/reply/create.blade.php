<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ url('style.css') }}" type="text/css">
    <title>リプライ投稿画面</title>
</head>
<body>
    <h1>リプライ投稿画面</h1>
    @error('text')
        <p class="error">{{ $message }}</p>
    @enderror
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