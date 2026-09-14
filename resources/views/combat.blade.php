<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Лига Героев</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/index.css', 'resources/js/fight.js'])
    </head>
    <body>
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Оппоненты в бою&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle" id="opponents">
                Загрузка...
            </div>
        </div>
        <br />
        <div class="main main--light">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Свитки и последователи&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                @foreach($scrolls as $i => $scroll)
                <p class="item {{ $i % 2 == 0 ? 'light' : '' }}">
                    <button style="position:relative;float:right;" type="button" onclick="useArmy('{{ $scroll['id'] }}')">Выбрать</button>
                    <img src="https://www.fantasyland.ru{{ $scroll['image'] }}" width="20" height="20" align="absmiddle" />&nbsp;
                    <small>{!! $scroll['title'] !!}</small>
                </p>
                @endforeach
                <p>
                    <button type="button" onclick="showArmy(0)">Bce</button>
                    <button type="button" onclick="showArmy(1)">Драконы</button>
                    <button type="button" onclick="showArmy(2)">Рыцари</button>
                    <button type="button" onclick="showArmy(3)">Дамы</button>
                </p>
                @foreach($army as $i => $item)
                <table class="{{ $i % 2 == 0 ? 'light' : '' }} army-type army-type_{{ $item['type'] }}" id="army_{{ $item['id'] }}" data-count="{{ $item['count'] }}" width="100%" colspacing="0" cellpadding="0">
                    <tr>
                        <td style="width: 80px" valign="top">
                            <strong class="main_middle__count">{{ $item['count'] }}</strong>
                            <a href="/cgi/army_desc.php?id=`{{ $item['id'] }}`">
                                <img src="https://www.fantasyland.ru/images/armies/{{ $item['image'] }}" width="70" height="70" />
                            </a>
                        </td>
                        <td valign="top">
                            <button style="position:relative;float:right;" type="button" onclick="useArmy('{{ $item['id'] }}')">Выбрать</button>
                            <small class="tiny">{!! $item['descr'] !!}</small>
                        </td>
                    </tr>
                </table>
                @endforeach
            </div>
        </div>
        <br />
    </body>
</html>