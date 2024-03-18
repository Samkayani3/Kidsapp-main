<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">


        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
            .alert-success{
                background-color: lightgreen;
                opacity: 0.5;
                width: 40%;
                margin: 0 30%;
                display: flex;
                flex-direction: row;
                justify-content: center;
                padding: 10px 50px;
                border-radius: 5px;
                font-weight: 500;
            }
            .register-btn{
                background-color: purple;
                padding: 15px 30px;
                border-radius: 5px;
                color:white;
                border: none;
                cursor: pointer;
            }
            .btn-left{
                display: flex;
                justify-content: end;
                text-decoration: none;
                cursor: context-menu;
            }
            .table{
                width: 100%;
                margin-top: 20px;

            }
            .text-center{
                text-align: center;
            }
            thead{
                text-align: left;
            }
        </style>
    </head>
   <body>
   @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h2 class="text-center">All Users Data</h2>
    <a href="/register" class="btn-left"><button class="register-btn">Register New User</button></a>

    <table border="1" class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
   </body>
</html>
