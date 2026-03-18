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
        <div class="main main--light">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Письмо&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <p>{{ $text }}</p>
                <table width="100%" style="table-layout: fixed;">
                    <tr>
                        <td>
                            <form method="POST" action="/cgi/change_info.php">
                                @csrf
                                <input type="hidden" name="option" value="4" />
                                <button type="submit">Назад</button>
                            </form>
                        </td>
                        @if($url)
                        <td align="right">
                            <form method="GET" action="{{ $url }}">
                                @csrf
                                <button type="submit">Ответить</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                </table>
            </div>
        </div>
        <br />
    </body>
</html>