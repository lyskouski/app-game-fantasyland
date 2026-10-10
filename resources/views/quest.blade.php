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
    <body>
        <div class="main">
            <div class="main_top">
                <div class="main_top__header">{{ $title }}</div>
            </div>
            <div class="main_middle">
                <p>{!! $description !!}</p>
                <br />
                <ul style="list-style-type: none; padding: 0;">
                @foreach($actions as $action)
                    <li style="margin-bottom: 12px;">
                        <form method="POST" action="/cgi/mc_hid.php">
                            @csrf
                            <input type="hidden" name="a1" value="{{ $action['id'] }}" />
                            <input type="hidden" name="a2" value="0" />
                            <input type="submit" value=">>" />
                            {{ $action['text'] }}
                        </form>
                    </li>
                @endforeach
                </ul>
                @if($timer)
                <div>
                    Время ожидания: <strong id="timer" data-seconds="{{ $timer }}" onclick="window.location = '/cgi/mc_hid.php';">-- : --</strong>
                </div>
                @endif
            </div>
        </div>
    </body>
</html>
