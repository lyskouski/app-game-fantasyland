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
                            <small><b>&nbsp;Деньги&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <center>
                    <table cellpadding=0 cellspacing=0 border=0 width=236>
                        <tr>
                            <td width=16 height=20 background="https://www.fantasyland.ru/images/miscellaneous/line_left.gif"></td>
                            <td width=16 height=20 background="https://www.fantasyland.ru/images/miscellaneous/line_gold.gif" title="Золото"></td>
                            <td width=86 height=20 background="https://www.fantasyland.ru/images/miscellaneous/line_center.gif" title="Золото" align="center"><b><span style="color:white">{{ $playerMoney }}</span></b></td>
                            <td width=16 height=20 background="https://www.fantasyland.ru/images/miscellaneous/line_uran.gif" title="Урановые Монеты"></td>
                            <td width=86 height=20 background="https://www.fantasyland.ru/images/miscellaneous/line_center.gif" title="Урановые Монеты" align="center"><b><span style="color:white">{{ $playerUM }}</span></b></td>
                            <td width=16 height=20 background="https://www.fantasyland.ru/images/miscellaneous/line_right.gif"></td>
                        </tr>
                    </table>
                </center>
                <div class="clear"></div>
            </div>
        </div>
        <br />
        <div class="main main--dark">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Информация о вещах&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <center>
                <table cellspacing=0 cellpadding=0 width="100%" border=0>
                    <tr>
                        <td width=55 align=center>
                            @if (isset($stuff[0]))
                            <img width=50 height=50 title="{{ $stuff[0]['title'] }}" src="{{ $stuff[0]['image'] }}" onclick='unwear(0)'>
                            @else
                            <img width="50" height="50" title="Место для шлема" src="https://www.fantasyland.ru/images/items/head_none.gif" onclick="wear('0', 'd10')">
                            @endif
                        </td>
                        <td rowspan=5 valign='middle' align='center'>
                            <img hspace=15 width=100 height=225 src="{{ $image }}" />
                        </td>
                        <td width=55>
                            @if (isset($stuff[1]))
                            <img width=50 height=50 title="{{ $stuff[1]['title'] }}" src="{{ $stuff[1]['image'] }}" onclick='unwear(1)'>
                            @else
                            <img width=50 height=50 title='Место для ожерелья'  src="https://www.fantasyland.ru/images/items/neck_none.gif" >
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td height=50 width=50>
                            @if (isset($stuff[2]))
                            <img width=50 height=50 title="{{ $stuff[2]['title'] }}" src="{{ $stuff[2]['image'] }}" onclick='unwear(2)'>
                            @else
                            <img width=50 height=50 title='Место для брони'  src="https://www.fantasyland.ru/images/items/body_none.gif" >
                            @endif
                        </td>
                        <td height=50 width=50>
                            @if (isset($stuff[3]))
                            <img width=50 height=50 title="{{ $stuff[3]['title'] }}" src="{{ $stuff[3]['image'] }}" onclick='unwear(3)'>
                            @else
                            <img width=50 height=50 title='Место для оружия'  src="https://www.fantasyland.ru/images/items/r_hand_none.gif" >
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td height=25>
                            @if (isset($stuff[16]))
                            <img width=25 height=25 title="{{ $stuff[16]['title'] }}" src="{{ $stuff[16]['image'] }}" onclick='unwear(16)'>
                            @else
                            <img width=25 height=25 title='Место для браслета'  src="https://www.fantasyland.ru/images/items/bracer_none.gif" >
                            @endif
                            @if (isset($stuff[17]))
                            <img width=25 height=25 title="{{ $stuff[17]['title'] }}" src="{{ $stuff[17]['image'] }}" onclick='unwear(17)'>
                            @else
                            <img width=25 height=25 title='Место для браслета'  src="https://www.fantasyland.ru/images/items/bracer_none.gif" >
                            @endif
                        </td>
                        <td height=25 width=50>
                            @if (isset($stuff[9]))
                            <img width=50 height=25 title="{{ $stuff[9]['title'] }}" src="{{ $stuff[9]['image'] }}" onclick='unwear(9)'>
                            @else
                            <img width=50 height=25 title='Место для пояса'  src="https://www.fantasyland.ru/images/items/belt_none.gif" >
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @if (isset($stuff[12]))
                            <img width=25 height=25 title="{{ $stuff[12]['title'] }}" src="{{ $stuff[12]['image'] }}" onclick='unwear(12)'>
                            @else
                            <img width=25 height=25 title='Место для кольца'  src="https://www.fantasyland.ru/images/items/r_ring_none.gif" >
                            @endif
                            @if (isset($stuff[13]))
                            <img width=25 height=25 title="{{ $stuff[13]['title'] }}" src="{{ $stuff[13]['image'] }}" onclick='unwear(13)'>
                            @else
                            <img width=25 height=25 title='Место для кольца'  src="https://www.fantasyland.ru/images/items/r_ring_none.gif" >
                            @endif
                            @if (isset($stuff[14]))
                            <img width=25 height=25 title="{{ $stuff[14]['title'] }}" src="{{ $stuff[14]['image'] }}" onclick='unwear(14)'>
                            @else
                            <img width=25 height=25 title='Место для кольца'  src="https://www.fantasyland.ru/images/items/r_ring_none.gif" >
                            @endif
                            @if (isset($stuff[15]))
                            <img width=25 height=25 title="{{ $stuff[15]['title'] }}" src="{{ $stuff[15]['image'] }}" onclick='unwear(15)'>
                            @else
                            <img width=25 height=25 title='Место для кольца'  src="https://www.fantasyland.ru/images/items/r_ring_none.gif" >
                            @endif
                        </td>
                        <td height=50>
                            @if (isset($stuff[4]))
                            <img width=50 height=50 title="{{ $stuff[4]['title'] }}" src="{{ $stuff[4]['image'] }}" onclick='unwear(4)'>
                            @else
                            <img width=50 height=50 title='Место для щита'  src="https://www.fantasyland.ru/images/items/l_hand_none.gif" >
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td height=50 width=50>
                            @if (isset($stuff[6]))
                            <img width=50 height=50 title="{{ $stuff[6]['title'] }}" src="{{ $stuff[6]['image'] }}" onclick='unwear(6)'>
                            @else
                            <img width=50 height=50 title='Место для перчаток'  src="https://www.fantasyland.ru/images/items/l_ring_none.gif" >
                            @endif
                        </td>
                        <td height=50 width=50>
                            @if (isset($stuff[8]))
                            <img width=50 height=50 title="{{ $stuff[8]['title'] }}" src="{{ $stuff[8]['image'] }}" onclick='unwear(8)'>
                            @else
                            <img width=50 height=50 title='Место для обуви'  src="https://www.fantasyland.ru/images/items/feet_none.gif" >
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            @if (isset($stuff[30]))
                            <img hspace=2 src="{{ $stuff[30]['image'] }}" width='25' height='25' border='0' title="{{ $stuff[30]['title'] }}" onclick='unwear(30)'>
                            @else
                            <img hspace=2 src="https://www.fantasyland.ru/images/items/scroll_none.gif" width='25' height='25' border='0' title='Место для свитка'>
                            @endif
                            @if (isset($stuff[31]))
                            <img hspace=2 src="{{ $stuff[31]['image'] }}" width='25' height='25' border='0' title="{{ $stuff[31]['title'] }}" onclick='unwear(31)'>
                            @else
                            <img hspace=2 src="https://www.fantasyland.ru/images/items/scroll_none.gif" width='25' height='25' border='0' title='Место для свитка'>
                            @endif
                            @if (isset($stuff[32]))
                            <img hspace=2 src="{{ $stuff[32]['image'] }}" width='25' height='25' border='0' title="{{ $stuff[32]['title'] }}" onclick='unwear(32)'>
                            @else
                            <img hspace=2 src="https://www.fantasyland.ru/images/items/scroll_none.gif" width='25' height='25' border='0' title='Место для свитка'>
                            @endif
                            @if (isset($stuff[33]))
                            <img hspace=2 src="{{ $stuff[33]['image'] }}" width='25' height='25' border='0' title="{{ $stuff[33]['title'] }}" onclick='unwear(33)'>
                            @else
                            <img hspace=2 src="https://www.fantasyland.ru/images/items/scroll_none.gif" width='25' height='25' border='0' title='Место для свитка'>
                            @endif
                            @if (isset($stuff[34]))
                            <img hspace=2 src="{{ $stuff[34]['image'] }}" width='25' height='25' border='0' title="{{ $stuff[34]['title'] }}" onclick='unwear(34)'>
                            @else
                            <img hspace=2 src="https://www.fantasyland.ru/images/items/scroll_none.gif" width='25' height='25' border='0' title='Место для свитка'>
                            @endif
                            @if (isset($stuff[35]))
                            <img hspace=2 src="{{ $stuff[35]['image'] }}" width='25' height='25' border='0' title="{{ $stuff[35]['title'] }}" onclick='unwear(35)'>
                            @else
                            <img hspace=2 src="https://www.fantasyland.ru/images/items/scroll_none.gif" width='25' height='25' border='0' title='Место для свитка'>
                            @endif
                            @if (isset($stuff[36]))
                            <img hspace=2 src="{{ $stuff[36]['image'] }}" width='25' height='25' border='0' title="{{ $stuff[36]['title'] }}" onclick='unwear(36)'>
                            @else
                            <img hspace=2 src="https://www.fantasyland.ru/images/items/scroll_none.gif" width='25' height='25' border='0' title='Место для свитка'>
                            @endif
                            @if (isset($stuff[37]))
                            <img hspace=2 src="{{ $stuff[37]['image'] }}" width='25' height='25' border='0' title="{{ $stuff[37]['title'] }}" onclick='unwear(37)'>
                            @else
                            <img hspace=2 src="https://www.fantasyland.ru/images/items/scroll_none.gif" width='25' height='25' border='0' title='Место для свитка'>
                            @endif
                        </td>
                    </tr>
                </table>
                </center>
                <script>
                    function unwear(slot) {
                        window.location.href = '/cgi/inv_unwear.php?id=' + slot;
                    }
                    function wear(tp, dv) {
                        window.location.href = '/cgi/inv_load_items.php?tp=' + tp + '&dv=' + dv + '&expand';
                    }
                </script>
            </div>
        </div>
        <br />
    </body>
</html>