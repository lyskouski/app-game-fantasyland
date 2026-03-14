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
        @foreach ($data as $header)
        <br />
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b style="color:white">&nbsp;{{ $header['name'] }}&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <table border="1" background="https://www.fantasyland.ru/images/pic.new/battle_bg.jpg">
                @foreach ($header['items'] as $item)
                <tr>
                    <td width="400" valign="top">
                        <a href="{{ $item['link'] }}">{{ $item['name'] }}</a><br />
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
        <br />
    </body>
</html>