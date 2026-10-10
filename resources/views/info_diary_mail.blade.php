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
    <body><div class="body">
        <div class="main main--light">
            <div class="main_top">
                <div class="main_top__header">Письмо</div>
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
                        <td>
                            <button onclick="window.location.href='/cgi/msgs_del.php?dt={{ $date }}'">Удалить</button>
                        </td>
                        <td align="right">
                            <button onclick="window.location.href='{{ $url }}'">Ответить</button>
                        </td>
                        @endif
                    </tr>
                </table>
            </div>
        </div>
    </div></body>
</html>
