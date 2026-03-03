<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Лига Героев</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/login.css', 'resources/js/ping.js'])
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
                            <small><b>&nbsp;{{ $title }}&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <img src="https://www.fantasyland.ru/{{ $image }}" width="100%" />
            </div>
        </div>
        <br />
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15" />
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Добыча / Крафт&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <div>{!! $data !!}</div>
                <br />
                <p>Время ожидания: <strong id="timer" data-seconds="{{ $timer }}">-- : --</strong></p>
            </div>
        </div>
        <br />
        <script>
            const timerElement = document.getElementById('timer');
            let seconds = parseInt(timerElement.getAttribute('data-seconds'), 10);

            function updateTimer() {
                if (seconds > 0) {
                    seconds--;
                    timerElement.textContent = getTime(seconds);
                } else {
                    clearInterval(timerInterval);
                    window.location = '/cgi/work_stop.php';
                }
            }

            function getTime(a) {
                h = Math.round( a / 3600 - 0.5 );
                m = Math.round( ( a / 60 ) % 60 - 0.5 );
                s = Math.round( a % 60 );

                if (s == 60) {
                    ++ m;
                    s = 0;
                }

                if (h >= 1) {
                    d = '';
                    if (h >= 24) {
                        d = Math.floor(h / 24);
                        h -= d * 24;
                        d = d + 'дн. ';
                    }
                    res = d + h + ":" + ( ( m < 10 ) ? "0" : "" ) + m + ":" + ( ( s < 10 ) ? "0" : "" ) + s;
                } else {
                    res = m + ":" + ( ( s < 10 ) ? "0" : "" ) + s;
                }
                return res;
            }

            const timerInterval = setInterval(updateTimer, 1000);
        </script>
    </body>
</html>