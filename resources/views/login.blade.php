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
        <div class="header">Лига Героев</div>
        <br />
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Авторизация&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <form method="POST" action="/login.php">
                    @csrf
                    <table align="center">
                        <tr>
                            <td class="cell" width="80" align="left">
                                <b>Логин:</b>
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
                                <input type=password name=password size="16" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="cell" width="100" align="right">
                                <input type=submit value="Войти" />
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
                <form method="GET" action="/registration">
                    @csrf
                    <center><input type=submit value="Зарегистрироваться" /></center>
                </form>
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
                            <small><b>&nbsp;Гостевой вход&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <form method="GET" action="/guestlogin.php">
                    @csrf
                    <input type="hidden" name="t" value="{{ $timestamp }}" />
                    <center><input type=submit value="Войти" /></center>
                </form>
            </div>
        </div>
    </body>
</html>