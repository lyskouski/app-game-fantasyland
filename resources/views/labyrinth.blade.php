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
    <body>
        <br />
        <br />
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Карта&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                ... карта лабиринта ...
            </div>
        </div>
        <br />
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small>
                                &nbsp;
                                <b>L-{{ $lvl }} ({{ $x }}, {{ $y }})</b>
                                - бодрость: <span id="stamina">{{ $stamina }}</span>%
                                &nbsp;
                            </small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <center>
                    <table border="0">
                        <tr>
                        @for($i = 0; $i < 9; $i++)
                            @if($i % 3 == 0 && $i != 0)
                            </tr><tr>
                            @endif
                            <td>
                                <img id="btn{{ $map[$i]['id'] }}" title="{{ $map[$i]['title'] }}" onclick="goTo({{ $map[$i]['num'] }})" border="0" height="28" width="28" src="https://www.fantasyland.ru/images/miscellaneous/{{ $map[$i]['img'] }}" />
                            </td>
                        @endfor
                        </tr>
                    </table>
                </center>
                <hr />
            </div>
        </div>
        <br />
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Свитки&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                @foreach($scrolls['items'] as $i => $item)
                <table class="{{ $i % 2 == 0 ? 'light' : '' }}" width="100%" colspacing="0" cellpadding="0">
                    <tr>
                        <td style="width: 80px" valign="top">
                            <strong class="main_middle__count">{{ $item['count'] }}</strong>
                            <img src="{{ $item['image'] }}" width="70" height="70" />
                        </td>
                        <td valign="top">
                            @if($item['wearable'])
                            <form method="GET" action="/cgi/inv_wear">
                                <input type="hidden" name="id" value="{{ $item['id'] }}" />
                                <input style="position:relative;float:right;" type="submit" value="Применить" />
                            </form>
                            @endif
                            <small>
                                <strong>{!! $item['name'] !!}</strong><br />
                                {!! $item['lvl'] !!}<br />
                            </small>
                            <small class="tiny">{!! $item['effects'] !!}</small>
                        </td>
                    </tr>
                </table>
                @endforeach
            </div>
        </div>
        <br />
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Зелья&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                @foreach($potions['items'] as $i => $item)
                <table class="{{ $i % 2 == 0 ? 'light' : '' }}" width="100%" colspacing="0" cellpadding="0">
                    <tr>
                        <td style="width: 80px" valign="top">
                            <strong class="main_middle__count">{{ $item['count'] }}</strong>
                            <img src="{{ $item['image'] }}" width="70" height="70" />
                        </td>
                        <td valign="top">
                            @if($item['wearable'])
                            <form method="GET" action="/cgi/inv_wear">
                                <input type="hidden" name="id" value="{{ $item['id'] }}" />
                                <input style="position:relative;float:right;" type="submit" value="Применить" />
                            </form>
                            @endif
                            <small>
                                <strong>{!! $item['name'] !!}</strong><br />
                                {!! $item['lvl'] !!}<br />
                            </small>
                            <small class="tiny">{!! $item['effects'] !!}</small>
                        </td>
                    </tr>
                </table>
                @endforeach
            </div>
        </div>
        <br />
    </body>
</html>