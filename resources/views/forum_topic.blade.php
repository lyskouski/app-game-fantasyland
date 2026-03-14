<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/index.css', 'resources/js/forum.js'])
    </head>
    <body>
        <br />
        <br />
        <div class="main">
            <div class="main_middle">
                <h2>{!! $title !!}</h2>
                Автор темы: {!! $author !!}
            </div>
        </div>
        @foreach ($items as $item)
        <div class="main main--light">
            <div class="main_middle">
                <small>{{ $item['time'] }} {!! $item['author'] !!}</small>
                <hr />
                {!! $item['content'] !!}
            </div>
        </div>
        @endforeach
        <div class="main">
            <div class="main_middle">
                <a href="{{ $back }}">Назад</a>
                @foreach ($pages as $page)
                | <a href="{{ $page['url'] }}">&nbsp;{{ $page['title'] }}&nbsp;</a>
                @endforeach
            </div>
        </div>
        @if ($hasForm)
        <div class="main">
            <div class="main_middle">
            <center>
                <form method="POST" action="/cgi/f_show_thread.php?rid={{ $rid }}">
                    <input type="hidden" name="postedd" value="1" />
                    <input name='thread_id' type='hidden' value="{{ $id }}" />
                    <textarea name="message" id="ta_message" style="width:100%;height:200px" onkeyup="storeCaret(this);" onselect="storeCaret(this);" onclick="storeCaret(this);"></textarea>
                    <input type="button" value="Ж" style="width:38px; font-weight:bold;" onClick="f1('[ж]','[жж]')" />
                    <input type="button" value="К" style="width:38px; font-style:italic;" onClick="f1('[к]','[кк]')" />
                    <input type="button" value="Ч" style="width:38px; text-decoration underline;" onClick="f1('[п]', '[пп]')">&nbsp;&nbsp;
                    <input type="button" value="красный" style="color: red;" onClick="f1('[красный]', '[цвет]')" />
                    <input type="button" value="синий" style="color: blue;" onClick="f1('[синий]', '[цвет]')" />
                    <br />
                    <br />
                    <input type="button" value="Персонаж" onClick="f1('[и=',']')" />
                    <!-- input type="button" name="quoteselected" value="Цитировать выделенное" onclick="quoteSelection()" -->
                    <br />
                    <br />
                    <input type="submit" name="ans" value="Ответить" />
                </form>
            </center>
            </div>
        </div>
        @endif
        <br />
    </body>
</html>