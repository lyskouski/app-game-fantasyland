<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Лига Героев</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/index.css', 'resources/js/ping.js', 'resources/js/timer.js'])
    </head>
    <body><div class="body">
        <div class="main">
            <div class="main_top">
                <div class="main_top__header">{{ $title }}</div>
            </div>
            <div class="main_middle">
                <img src="https://www.fantasyland.ru/{{ $image }}" width="100%" />
            </div>
        </div>

        <div class="main">
            <div class="main_top">
                <div class="main_top__header">Добыча / Крафт</div>
            </div>
            <div class="main_middle">
                <p>{{ $data }}</p>
                <br />
                <p>Время ожидания: <strong id="timer" data-seconds="{{ $timer }}" onclick="window.location = '/cgi/work_stop.php?_={{ $timer }}';">-- : --</strong></p>
                <br />
                <p>
                    <form action="/cgi/work_stop.php" method="GET">
                        <input type="hidden" name="status" value="0" />&nbsp;
                        <input type="submit" value="Остановить" />
                    </form>
                </p>
            </div>
        </div>
    </div></body>
</html>
