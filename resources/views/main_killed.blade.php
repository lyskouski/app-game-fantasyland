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
                <div class="main_top__header">Другой Мир</div>
            </div>
            <div class="main_middle">
                <p>{{ $description }}</p>
                <p>
                    Вам предстоит провести здесь еще:
                    <b id="timer" data-seconds="{{ $timer }}" onclick="window.location = '{{ $link }}';">--:--</b>
                </p>
            </div>
        </div>
    </div></body>
</html>