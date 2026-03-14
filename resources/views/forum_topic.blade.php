<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/index.css'])
    </head>
    <body>
        <br />
        <br />
        <div class="main">
            <div class="main_middle">
                <h2>{!! $title !!}</h2>
                Автор темы: {!! $author !!}
            </div>
        </div>
        @foreach ($items as $item)
        <div class="main main--light">
            <div class="main_middle">
                <small>{{ $item['time'] }} {!! $item['author'] !!}</small>
                <hr />
                {!! $item['content'] !!}
            </div>
        </div>
        @endforeach
        <div class="main">
            <div class="main_middle">
                <a href="">Назад</a>
                @foreach ($pages as $page)
                | <a href="{{ $page['url'] }}">&nbsp;{{ $page['title'] }}&nbsp;</a>
                @endforeach
            </div>
        </div>
        <br />
    </body>
</html>