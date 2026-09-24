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
    <body style="padding-bottom: 56px;">
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Управление&nbsp;</b></small>
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
                    <form method="GET" action="/chat/clear">
                        <button type="submit" class="button">Очистить</button>
                    </form>
                </center>
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
                            <small><b>&nbsp;Сообщения&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle" id="chat-messages">
                @foreach($data as $item)
                <p class="item"><small>[{{ $item->created_at->format('H:i') }}] {!! $item->message !!}</small></p>
                @endforeach
            </div>
        </div>
        <br />
        <form method="GET" action="/chinp" class="chat-input">
            <input type="hidden" name="chn" value="0">
            <input type="text" id="chat_message" name="a" autocomplete="off" placeholder="Сообщение...">
            <button type="submit" class="button">Отправить</button>
        </form>
        <script>
            setInterval(() => {
                fetch(window.location.href)
                    .then((response) => response.text())
                    .then((html) => {
                        const next = new DOMParser().parseFromString(html, 'text/html').getElementById('chat-messages');
                        const current = document.getElementById('chat-messages');
                        if (next && current) {
                            current.innerHTML = next.innerHTML;
                        }
                    });
            }, 5000);
        </script>
    </body>
</html>
