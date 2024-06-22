<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome</title>
</head>

<body>
    <h2>Welcome @auth {{ auth()->user()->name }} @endauth
    </h2>

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


    @guest
        <div>You are currently not logged in</div>
        <a href="{{ route('login.page') }}">Login</a> &nbsp;&nbsp;
        <a href="{{ route('signup.page') }}">Signup</a>
    @endguest

    @auth
        <div>Name: {{ Auth::user()->name }}</div>
        <div>Email: {{ auth()->user()->email }}</div>
        <div>Role: </div>
        <br>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button>LOGOUT</button>
        </form>
   
        <br>
    @if (auth()->user()->hasRole('admin'))
        <hr>USER IS ADMIN
        <hr>
    @endif
    @if (auth()->user()->hasRole('editor'))
        <hr>USER IS EDITOR
        <hr>
    @endif

    <br>
    <h3>Permissions : </h3>
    <ul>
        @foreach (auth()->user()->getAllPermissions() as $permission)
            <li>{{ $permission->name }}</li>
        @endforeach
    </ul>

    @endauth

</body>

</html>
