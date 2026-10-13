<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Лига Героев</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/index.css'])
    </head>
    <body><div class="body">
        <div style="min-height:30%">&nbsp;</div>
        <div class="main">
            <div class="main_top">
                <div class="main_top__header">Смена почты</div>
            </div>
            <div class="main_middle">
                <form method="POST" action="/account/email">
                    @csrf
                    <p>
                        <label for="password">Текущий пароль</label><br />
                        <input type="password" name="password" required />
                    </p>
                    <p>
                        <label for="email">Новый e-mail</label><br />
                        <input type="email" name="email" required />
                    </p>
                    <p>
                        <input type="submit" value="Сменить почту" />
                    </p>
                </form>
            </div>
        </div>
    </div>
    </body>
</html>