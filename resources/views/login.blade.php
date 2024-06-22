<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    @if (session()->has('error'))
        <hr>
        <div>{{ session('error') }}</div>
        <hr>
    @endif

    @if (session()->has('success'))
        <hr>
        <div>{{ session('success') }}</div>
        <hr>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div>Email : <input type="email" name="email"></div>
        @error('email')
            <small>{{ $message }}</small>
        @enderror
        <br><br>
        <div>Password : <input type="password" name="password"></div>
        @error('password')
            <small>{{ $message }}</small>
        @enderror
        <br><br>
        <input type="submit" value="submit">
    </form>

</body>
</html>