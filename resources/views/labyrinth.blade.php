<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Лига Героев</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/index.css', 'resources/css/labyrinth.css', 'resources/js/ping.js', 'resources/js/labyrinth.js'])
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
                            &nbsp;
                            <small>
                                <b id="location">{{ $location['title'] }}:</b>
                                &nbsp;
                                <span id="source" data-id="{{ $location['id'] }}" onclick="getSource()">
                                    <img src="/images/lab/mob.gif" width="10" height="10" border="0" title="Мобы" />
                                    <span id="source_mob">?</span>
                                    &nbsp;
                                    <img src="/images/lab/drop.gif" width="10" height="10" border="0" title="Дроп" />
                                    <span id="source_drop">?</span>
                                </span>
                            </small>
                            &nbsp;
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                @foreach($active_potions as $item)
                <div class="main--dark tiny potions_item">
                    <img hspace=2 src="{{ $item['image'] }}" width='25' height='25' border='0' title="{{ $item['title'] }}"/>&nbsp;{{ $item['time'] }}
                </div>
                @endforeach
                <div class="clear"></div>
                <canvas id="cimap" data-version="{{ \App\Settings\Defines::PLUGIN_VERSION }}" data-login="{{ $login }}" data-place="{{ $place }}" data-loc="{{ $loc }}" data-x="{{ $location['x'] }}" data-y="{{ $location['y'] }}" data-z="{{ $location['z'] }}" onmousemove="canvasMouseMove(event)" onmousedown="canvasMouseDown(event)" onmouseup="canvasMouseUp(event)" onmouseleave="canvasMouseOut(event)" ontouchstart="canvasTouchStart(event)" ontouchmove="canvasTouchMove(event)" ontouchend="canvasTouchEnd(event)" ontouchcancel="canvasTouchCancel(event)"></canvas>
                <div id="return_focus" title="Вернуть фокус на персонажа"></div>
                <script>
                    document.addEventListener('DOMContentLoaded', () => window.drawMap({!! $map_data !!}));
                </script>
                <div id="cibuffer" style="display:none"></div>
                <div>
                    <small>
                        <a style="float:right;padding-top:2px" href="/labyrinth/config">Настройки</a>
                        Масштаб:
                        <a href="#more" onclick="canvasZoomOut();return false">меньше</a>
                        &nbsp;/&nbsp;
                        <a href="#less" onclick="canvasZoomIn(); return false">больше<a>
                    </small>
                </div>
                <div id="message"></div>
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
                                <b id="position" style="color:white">L-{{ $lvl }} ({{ $x }}, {{ $y }})</b>
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
                    <div id="cod" style="display:none">
                        <img id="codImage" src="{!! $captcha !!}" width="90" height="40" />
                        &nbsp;<input type="text" id="codInput" inputmode="numeric" maxlength="4" placeholder="Введите код" />
                    </div>
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
                <div id="items"></div>
                <div id="quest"></div>
                <div id="fight"></div>
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
