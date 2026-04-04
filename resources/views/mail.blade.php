<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Лига Героев</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/index.css', 'resources/js/timer.js'])
    </head>
    <body>
        <br />
        <br />
        @if($title)
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
                {!! $message !!}
            </div>
        </div>
        <br />
        @endif
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
                <form method="POST" action="/cgi/change_info.php">
                    @csrf
                    <input type="hidden" name="option" value="4" />
                    <button style="float:right" type="submit">Назад</button>
                </form>
                <br /><br />
                @if($timer)
                <p>{{ $notification }}</p>
                <p>Время ожидания: <strong id="timer" data-seconds="{{ $timer }}" onclick="this.innerHTML = 'можно отправлять';">-- : --</strong></p>
                @endif
                <form method="POST" action="/cgi/send_letter.php?id={{ $id }}">
                    @csrf
                    <input type="hidden" name="lenInp" value="50" />
                    <input type="hidden" name="CheckSum" value="" />
                    <input type="hidden" name="descLen" value="" />
                    <input style="width: 100%" type="text" name="char_name" placeholder="Имя персонажа" required value="{{ $name }}" />
                    <br /><br />
                    <textarea style="width: 100%;height:150px" name="message" placeholder="Сообщение" maxlength="255" required></textarea>
                    <br /><br />
                    <button type="submit">Отправить</button>
                </form>
            </div>
        </div>
        <br />
    </body>
</html>