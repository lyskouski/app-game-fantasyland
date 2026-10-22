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
                <div class="main_top__header">{{ $isAdult ? 'Раздел недоступен' : 'Доступ ограничен' }}</div>
            </div>
            <div class="main_middle">
                @if($isAdult)
                    <p>Чат, почта и форум отключены родительским контролем.</p>
                    <form method="GET" action="/parental">
                        <input type="submit" style="width:100%" value="Родительский контроль" />
                    </form>
                    <br />
                    <form method="GET" action="/cgi/no_combat.php">
                        <input type="submit" style="width:100%" value="На главную" />
                    </form>
                @else
                    <p>Приложение доступно только с {{ $adultAge }} лет.</p>
                @endif
            </div>
        </div>
    </div>
    </body>
</html>
