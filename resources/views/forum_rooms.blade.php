<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/index.css'])
    </head>
    <body><div class="body">
        @foreach ($data as $header)
        <div class="main">
            <div class="main_top">
                <div class="main_top__header">{{ $header['name'] }}</div>
            </div>
            <div class="main_middle">
                <table border="1" background="https://www.fantasyland.ru/images/pic.new/battle_bg.jpg">
                @foreach ($header['items'] as $item)
                <tr>
                    <td width="400" valign="top">
                        <a href="{{ $item['link'] }}">{!! $item['name'] !!}</a><br />
                        <sub>{{ $item['description'] }}</sub>
                    </td>
                    <td width="250" valign="top">
                        <small>Автор последнего сообщения: <strong>{{ $item['author'] }}</strong></small><br />
                        <sub>Дата: {{ $item['time'] }}</sub>
                    </td>
                @endforeach
                </table>
            </div>
        </div>
        @endforeach
    </div></body>
</html>
