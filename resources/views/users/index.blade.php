<!-- resource/view/users/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ url('style.css') }}" type="text/css">
    <title>User Lists</title>
</head>
<body>
    <h1>ユーザーリスト</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ファーストネーム</th>
                <th>ラストネーム</th>
                <th>メールアドレス</th>
            </tr>
        </thead>
        <tbody>
            @csrf
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->first_name }}</td>
                    <td>{{ $user->last_name }}</td>
                    <td>{{ $user->email }}</td>
                </tr>
            
            @empty
                <ul>
                    <li>No Users!<li>
                </ul>
            @endforelse
        </tbody>
    </table>
</body>
</html>