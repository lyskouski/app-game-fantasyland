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
        <div class="main main--light">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b style="color:white">&nbsp;{{ $title }}&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                @foreach ($items as $item)
                    {!! $item['topic'] !!} <small>{!! $item['author'] !!}</small><br />
                    <small>{{ $item['description'] }}</small>
                    <hr />
                @endforeach
                <a href="/cgi/forum_rooms.php">Назад</a>
                @for ($i = 1; $i <= $pages; $i++)
                |&nbsp;<a href="/cgi/forum.php?rid={{ $id }}&p={{ $i }}">&nbsp;{{ $i }}&nbsp;</a>&nbsp;
                @endfor
            </div>
        </div>
        @if ($hasForm)
        <br />
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b style="color:white">&nbsp;Создать новую тему&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <form method="POST" action="/cgi/forum.php?rid={{ $rid }}&p={{ $p }}">
                    <input type="hidden" name="posted" value="1" />
                    Название темы:<br />
                    <input type="text" name="thread_name" style="width:100%" maxlength="255" />
                    <br />
                    <br />
                    <textarea name="message" id="ta_message" style="width:100%;height:200px" onkeyup="storeCaret(this);" onselect="storeCaret(this);" onclick="storeCaret(this);"></textarea>
                    <center>
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
                        <input type="submit" name="crt" value="Создать тему" />
                    </center>
                </form>
            </div>
        </div>
        @endif
        <br />
    </body>
</html>
