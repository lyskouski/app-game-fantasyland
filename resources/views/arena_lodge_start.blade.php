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
                <img src="https://www.fantasyland.ru/{{ $image }}" class="location" />
                <small>{{ $description }}</small>
                <div class="clear"></div>
            </div>
        </div>

        <div class="main">
            <div class="main_top">
                <div class="main_top__header">Ложа</div>
            </div>
            <div class="main_middle">
                <p>
                    <i>
                        Вы устроились поудобнее в компании отдыхающих.
                        При необходимости можно досрочно
                        <a href="/cgi/tribune_work.php?type=break">остановить</a>.
                    </i>
                </p>
                <br />
                <p>
                    Время ожидания:
                    <strong id="timer" data-seconds="{{ $timer }}" onclick="window.location = '/cgi/tribune_work.php?type=stop'">-- : --</strong>
                </p>
            </div>
        </div>
    </div></body>
</html>
