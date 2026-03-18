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
                            <small><b>&nbsp;Календарь&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                @foreach($calendar as $item)
                <div class="{{ $item[2] }}">
                    <img src="{{ $item[0] }}" width="15" height="15" />&nbsp;
                    <small>{{ $item[1] }}</small>
                </div>
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
                            <small><b>&nbsp;Почта&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <script>
                    function toggleMailTab(tab) {
                        document.querySelectorAll('#mail_income, #mail_sent').forEach(t => {
                            t.style.display = 'none';
                        });
                        document.querySelector(tab).style.display = 'block';
                    }
                </script>
                <center>
                    <a href="#" onclick="toggleMailTab('#mail_income')">Входящие</a> |
                    <a href="#" onclick="toggleMailTab('#mail_sent')">Исходящие</a> |
                    <a href="/cgi/send_letter.php?id=2956">Написать</a>
                </center>
                <br />
                <div id="mail_income">
                    <table width="100%" style="table-layout: fixed;">
                        @foreach($income as $i => $mail)
                        <tr class="{{ $i % 2 == 0 ? 'light' : '' }}" onclick="window.location.href='/cgi/msgs_read.php?dt={{ $mail['date'] }}&m=0'">
                            <td style="width: 15px; flex-shrink: 0;">
                                <img src="https://www.fantasyland.ru/images/miscellaneous/post_{{ $mail['toRead'] ? 'new' : 'old' }}.gif" width="15" height="15" />
                            </td>
                            <td class="nowrap" style="width: 70px; flex-shrink: 0; overflow: hidden;">
                                <small><b>{{ $mail['author'] }}</b></small>
                            </td>
                            <td class="nowrap" style="overflow: hidden;">
                                <small>{{ $mail['content'] }}</small>
                            </td>
                            <td class="nowrap" style="width: 50px; flex-shrink: 0;" align="right">
                                <small>{{ $mail['ndate'] }}</small>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <div id="mail_sent" class="hidden">
                    <table width="100%" style="table-layout: fixed;">
                        @foreach($outcome as $i => $mail)
                        <tr class="{{ $i % 2 == 0 ? 'light' : '' }}" onclick="window.location.href='/cgi/letters_read.php?id={{ $mail['id'] }}'">
                            <td class="nowrap" style="width: 70px; flex-shrink: 0; overflow: hidden;">
                                <small><b>{{ $mail['author'] }}</b></small>
                            </td>
                            <td class="nowrap" style="overflow: hidden;">
                                <small>{{ $mail['content'] }}</small>
                            </td>
                            <td class="nowrap" style="width: 50px; flex-shrink: 0;" align="right">
                                <small>{{ $mail['ndate'] }}</small>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
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
                            <small><b>&nbsp;История&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                ...
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
                            <small><b>&nbsp;Дневник заданий&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                ...
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
                            <small><b>&nbsp;Блокнот&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                ...
            </div>
        </div>
        <br />
    </body>
</html>