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
        <div class="main">
            <div class="main_top">
                <div class="main_top__header">Локация</div>
            </div>
            <div class="main_middle">
                <img src="https://www.fantasyland.ru/images/{{ $image }}" width="100%" />
            </div>
        </div>

        <div class="main">
            <div class="main_top">
                <div class="main_top__header">Локации для перехода</div>
            </div>
            <div class="main_middle">
                @foreach ($map as $location)
                <form method="POST" action="/cgi/no_combat.php" style="margin-bottom: 8px;">
                    @csrf
                    <input type="hidden" name="locat" value="{{ $location['id'] }}" />
                    <input type="hidden" name="additional" value="0" />
                    <button type="submit" style="width: 100%;">{!! $location['loc'] !!}</button>
                </form>
                @endforeach
                @if (isset($hasRoad) && $hasRoad)
                    <br />
                    <form method="GET" action="/cgi/map.php" style="margin-bottom: 8px;">
                        @csrf
                        <input type="submit" value="Покинуть локацию" style="width: 100%;" />
                    </form>
                @endif
            </div>
        </div>
    </div></body>
</html>
