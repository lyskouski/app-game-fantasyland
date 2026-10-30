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
                <div class="main_top__header">Эффекты 13+</div>
            </div>
            <div class="main_middle">
                <form method="GET" action="/info/effects13">
                    <input type="radio" name="type" value="use" checked />&nbsp;Применить
                    <input type="radio" name="type" value="learn" />&nbsp;Изучить
                    <table width="100%" style="table-layout: fixed;">
                        @foreach($effects as $i => $effect)
                        <tr class="{{ $i % 2 == 0 ? 'light' : '' }}">
                            <td style="width: 10px" valign="middle">
                                <input type="radio" name="id" value="{{ $effect['id'] }}" />
                            </td>
                            <td style="width: 20px" valign="middle">
                                <strong class="main_middle__count">{{ $effect['count'] }}</strong>
                                <img src="https://www.fantasyland.ru/images/medals/{{ $effect['image'] }}" width="20" height="20" />
                            </td>
                            <td valign="top">
                                @if($effect['type'] == 'learn')
                                <strong>Не изучено: </strong>
                                @endif
                                <small>{!! $effect['description'] !!}</small>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    Применить на игрока:<br/>
                    <input name="login" type="text" value="" />
                    <br /><br />
                    <input type="submit" value="Применить" />
                </form>
            </div>
        </div>
    </div></body>
</html>
