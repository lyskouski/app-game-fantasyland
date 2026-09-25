<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Лига Героев</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/index.css', 'resources/js/ping.js'])
    </head>
    <body>
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15" />
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;{{ $title }}&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <img src="https://www.fantasyland.ru/{{ $image }}" class="location" />
                <small>{{ $description }}</small>
                <div class="clear"></div>
            </div>
        </div>

        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Опции&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                @foreach ($map as $location)
                <form method="GET" action="/cgi/arena.php" style="margin-bottom: 8px;">
                    @csrf
                    <input type="hidden" name="g" value="{{ $location['id'] }}" />
                    <input type="submit" @if($location['id'] == $current) disabled @endif value="{{ $location['loc'] }}" style="width: 100%;" />
                </form>
                @endforeach
                <br />
                @foreach ($place as $location)
                <form method="POST" action="/cgi/no_combat.php" style="margin-bottom: 8px;">
                    @csrf
                    <input type="hidden" name="locat" value="{{ $location['id'] }}" />
                    <input type="hidden" name="additional" value=0 />
                    <input type="submit" value="{{ $location['loc'] }}" style="width: 100%;" />
                </form>
                @endforeach
            </div>
        </div>

        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Дуэли&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                @if($create)
                <p>Вы можете подать заявку на дуэль</p>
                <form action="/cgi/arena.php" method="GET">
                    <input type="hidden" name="a" value="1" />
                    <input type="hidden" name="qn" value="2" />
                    <table>
                        <tr class="light">
                            <td>Таймаут:</td>
                            <td align="right">
                                <select name="to">
                                    <option value="0.5">30 сек.</option>
                                    <option value="1">1 мин.</option>
                                    <option value="2">2 мин.</option>
                                    <option value="3" selected>3 мин.</option>
                                    <option value="4">4 мин.</option>
                                    <option value="5">5 мин.</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Минимальный уровень:</td>
                            <td align="right"><input type="text" size=2 maxlength=2 name="fl" /></td>
                        </tr>
                        <tr class="light">
                            <td>Максимальный уровень:</td>
                            <td align="right"><input type="text" size=2 maxlength=2 name="tl" /></td>
                        </tr>
                        <tr>
                            <td>Бой с артефактами:</td>
                            <td align="center"><input type="hidden" name="w_art" value="0" /><input type="checkbox" name="w_art" value="1" /></td>
                        </tr>
                    </table>
                    <input type="submit" value="Создать" />
                </form>
                @else
                <p>
                    @if($decline)
                    <script>
                        setTimeout(() => {
                            const timestamp = Date.now();
                            window.location.href = '/cgi/arena.php?_=' + timestamp + '#fight-list';
                        }, 7000);
                    </script>
                    Вы можете <a href="/cgi/arena.php?{{ $decline }}">отозвать свою заявку</a>
                    @endif
                    @if($accept)
                    ,&nbsp;или <a href="/cgi/arena.php?{{ $accept }}">начать бой</a>.
                    @endif
                </p>
                @endif
                <br />
                @foreach ($groups as $group)
                <table border="1" width="100%">
                    <tr>
                        <td rowspan="2">{{ $group['time'] }}</td>
                        <td><small>{!! $group['opponent'] !!}</small></td>
                        <td rowspan="2">
                            {{ $group['conditions'] }}
                            @if($group['withoutArt'])
                            <img align="absmiddle" src="https://www.fantasyland.ru/images/miscellaneous/woart.gif" border="0" width="18" height="18" alt="Бой без артефактов" title="Бой без артефактов" />
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small>{!! $group['enemy'] !!}</small>
                            @if($group['attack'])
                            <a href="/cgi/arena.php?{{ $group['attack'] }}">Вызвать</a>
                            @endif
                        </td>
                    </tr>
                </table>
                @endforeach
                <a name="fight-list"></a>
            </div>
        </div>
    </body>
</html>
