<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{ asset('resources/css/app.css') }}" rel="stylesheet">

    <style>
        .text-red-500{
            color:red;
            font-weight: 500;
        }
    </style>
</head>
<body>
<form method="POST" action="{{ route('register-user') }}">
    @csrf

    <label for="name">Name:</label>
    <input type="text" name="name" id="name" value="{{ old('name') }}" required><br/>
    @error('name')
    <span class="text-red-500">{{ $message }}</span>
    @enderror<br/>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="{{ old('email') }}" required><br/>
    @error('email')
    <span class="text-red-500">{{ $message }}</span>
    @enderror
    <br/>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br/>
    @error('password')
    <span class="text-red-500">{{ $message }}</span>
    @enderror<br/>
    <button type="submit">Register</button>
</form>
<br/><br/>



<form method="POST" action="{{ route('password-reset') }}">
    @csrf

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="{{ old('email') }}" required><br/>
    @error('email')
    <span class="text-red-500">{{ $message }}</span>
    @enderror
    <button type="submit">Send Password Link</button>
</form>
</body>
</html>
