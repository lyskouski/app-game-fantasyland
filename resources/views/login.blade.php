<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <br />
        <br />
        <form method="POST" action="login.php">
            @csrf
            <table>
            <tr><td>Логин</td><td><input name=login size="16"></td></tr>
            <tr><td>Пароль</td><td><input type=password name=password size="16"></td></tr>
            <tr><td colspan=2 align=right><input type=submit value="Войти"></td></tr>
            </table>
        </form>
    </body>
</html>