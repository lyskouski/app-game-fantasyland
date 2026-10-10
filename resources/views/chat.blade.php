<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Лига Героев</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/index.css', 'resources/js/swipe.js'])
    </head>
    <body><div class="body" style="padding-bottom: 56px;">
        <button type="button" class="menu_toggle" onclick="toggleSidebar()">☰</button>
        <div id="menu_sidebar" class="menu_sidebar">
            <div id="user-list">Загрузка...</div>
            <br />
            <br />
        </div>

        <div class="main">
            <div class="main_top">
                <div class="main_top__header">Управление</div>
            </div>
            <div class="main_middle">
                <center>
                    <form method="GET" action="/chat/clear">
                        <button type="submit" class="button">Очистить</button>
                    </form>
                </center>
            </div>
        </div>

        <div class="main main--light">
            <div class="main_top">
                <div class="main_top__header">Сообщения</div>
            </div>
            <div class="main_middle" id="chat-messages">
                @foreach($data as $item)
                <p class="item">
                    <small>
                        <span
                            onclick="claim('{{ $item->id }}')"
                            @if(str_contains($item->message, $me))
                            style="color: maroon; font-weight: bold;"
                            @endif
                        >[{{ $item->created_at->format('H:i') }}]</span>&nbsp;
                        <span id="mssg{{ $item->id }}">{!! $item->message !!}</span>
                    </small>
                </p>
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
            window.claim = function (id) {
                const el = document.getElementById('mssg' + id);
                const text = el.textContent;
                if (confirm('Хотите пожаловаться? Сообщение: ' + text)) {
                    fetch('/cgi/claim?id=' + id)
                        .then((response) => response.text())
                        .then((text) => alert(text));
                    el.innerHTML = '-- жалоба отправлена --';
                }
            };

            window.getState = function () {
                fetch('/ch/chout.php')
                    .then((response) => response.text())
                    .then((html) => {
                        const next = new DOMParser().parseFromString(html, 'text/html').getElementById('chat-messages');
                        const current = document.getElementById('chat-messages');
                        if (next && current) {
                            current.innerHTML = next.innerHTML;
                        }
                    });
                fetch('/cgi/ch_ref.php')
                    .then((response) => response.text())
                    .then((html) => document.getElementById('user-list').innerHTML = html);
            };
            window.getState();
            setInterval(window.getState, 5000);
        </script>
    </div></body>
</html>
