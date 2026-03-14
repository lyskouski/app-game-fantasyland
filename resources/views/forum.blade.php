<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <br />
    </body>
</html>