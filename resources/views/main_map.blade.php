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
        <br />
        <br />
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15" />
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Карта Ледрака&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <img src="https://www.fantasyland.ru/images/places/map_new_3.png" width="100%" />
            </div>
        </div>
        <br />
        @if ($timer != null)
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15" />
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Время в пути&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                Направление: <strong>{{ $title }}</strong>, время в пути:&nbsp;
                <strong id="timer" data-seconds="{{ $timer }}" onclick="window.location = '/cgi/travel_stop.php';">-- : --</strong>
            </div>
        </div>
        <br />
        @endif
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Локации для путешествий&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                @foreach ($map as $location)
                <form method="POST" action="/cgi/travel_start.php" style="margin-bottom: 8px;">
                    @csrf
                    <input type="hidden" name="place_st" value="5" />
                    <input type="hidden" name="place_de" value="{{ $location['id'] }}" />
                    <input type="hidden" name="posted" value="1" />
                    <input type="hidden" name="attack" value="0" />
                    <input type="submit" value="{{ $location['loc'] }}" style="width: 100%;" />
                </form>
                @endforeach
                <br />
                <form method="GET" action="/cgi/no_travel.php" style="margin-bottom: 8px;">
                    @csrf
                    <input type="hidden" name="return" value="{{ $return }}" />
                    <input type="submit" value="Вернуться в локацию" style="width: 100%;" />
                </form>
            </div>
        </div>
        <br />
    </body>
</html>