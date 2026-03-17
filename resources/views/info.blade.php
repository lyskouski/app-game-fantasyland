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
                            <small><b>&nbsp;{{ $user }}&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                @foreach($data as $option)
                <form method="POST" action="/cgi/change_info.php">
                    @csrf
                    <input type="hidden" name="option" value="{{ $option['key'] }}" />
                    <table width="100%"><tr><td width="25">
                    <input width="20" height="20" type="image" src="{{ $option['image'] }}" />&nbsp;
                    </td><td>
                    <input type="submit" style="width:100%" value="{{ $option['value'] }}" />
                    </td></tr></table>
                </form>
                <br />
                @endforeach
                <form method="GET" action="/smallhelp0.html">
                    @csrf
                    <table width="100%"><tr><td width="25">
                    <input width="20" height="20" type="image" src="https://www.fantasyland.ru/images/i/b5.gif" />&nbsp;
                    </td><td>
                    <input type="submit" style="width:100%" value="Помощь Новичку" />
                    </td></tr></table>
                </form>
            </div>
        </div>
        <br />
    </body>
</html>