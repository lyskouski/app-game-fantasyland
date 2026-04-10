<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link rel="stylesheet" href="https://www.fantasyland.ru/css/f-style.css" type="text/css" />
        <link rel="stylesheet" href="https://www.fantasyland.ru/css/styles1.css" type="text/css" />
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
                            &nbsp;
                            Очистка
                            &nbsp;
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <br />
                <a href="/labyrinth/clear">Удалить все данные лабиринта</a>
                <br /><br />
                <a href="/labyrinth/clear?last_hour=true">Оставить данные только за последний час</a>
                <br /><br />
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
                            &nbsp;
                            Цитадель: синхронизация данных
                            &nbsp;
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <br />
                @if ($type)
                <div class="alert alert-success">
                    Ваш статус на Цитадели: <b>{{ $type }}</b><br />
                    <input type="checkbox" id="sync" onchange="localStorage.citadel_sync = this.checked ? '1' : '0'" />
                    &nbsp;Синхронизировать данные
                    <script>
                        document.addEventListener('DOMContentLoaded', () => document.getElementById('sync').checked = localStorage.citadel_sync === '1');
                    </script>
                </div>
                @else
                <a href="/labyrinth/sync">Подключиться к Цитадели</a>
                @endif
                <br /><br />
            </div>
        </div>
        <br />
    </body>
</html>