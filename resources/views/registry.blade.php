<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Лига Героев</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/login.css'])
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
                            <small><b>&nbsp;Регистрация&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <form method="POST" action="/cgi/register.php">
                    @csrf
                    <table align="center">
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="cell" width="80" align="left">
                                <b>Имя персонажа:</b>
                            </td>
                            <td>
                                <input name=login size="16" />
                            </td>
                        </tr>
                        <tr>
                            <td class="cell" width="80" align="left">
                                <b>Пароль:</b>
                            </td>
                            <td>
                                <input type=password name=password1 size="16" />
                            </td>
                        </tr>
                        <tr>
                            <td class="cell" width="80" align="left">
                                <b>Повтор пароля:</b>
                            </td>
                            <td>
                                <input type=password name=password2 size="16" />
                            </td>
                        </tr>
                        <tr>
                            <td class="cell" width="80" align="left">
                                <b>Email:</b>
                            </td>
                            <td>
                                <input type="email" name="email" size="16" />
                            </td>
                        </tr>
                        <tr>
                            <td class="cell" width="80" align="left">
                                <b>Пол:</b>
                            </td>
                            <td>
                                <input type="radio" name="sex" value="m" checked="">Мужской
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="sex" value="f">Женский
                            </td>
                        </tr>
                        <tr>
                            <td class="cell" width="80" align="left">
                                <b>Пригласил:</b>
                            </td>
                            <td>
                                <input name="inv_login" size="16" value="Росомаха" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="cell" width="80" align="left">
                                <input name="rules" type="checkbox" />
                                &nbsp;Обязуюсь соблюдать <a href="/rules.php">Правила</a> Лиги Героев!
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="cell" width="100" align="right">
                                <input type=submit name=submit value="Регистрация" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            @if(isset($error))
                <div>{{ $error }}</div>
            @endif
        </div>
        <br />
    </body>
</html>