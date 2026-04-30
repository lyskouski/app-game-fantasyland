<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Лига Героев</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/index.css', 'resources/js/main_place.js'])
    </head>
    <body>
        <br />
        <br />
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
        <br />
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
                    <input type="hidden" name="place_regime" value="{{ $location['id'] }}" />
                    <input type="hidden" name="addval" value=0 />
                    <input type="hidden" name="addval1" value=0 />
                    <input type="submit" value="{{ $location['loc'] }}" style="width: 100%;" />
                </form>
                @endforeach
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
                            <small><b>&nbsp;Тренировка последователей&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <form method="GET" action="/cgi/train_start.php">
                    <img src="{!! $captcha !!}" width="90" height="40" align="absmiddle" />&nbsp;
                    <input type="text" name="code" size="6" autocomplete="off" inputmode="numeric" />&nbsp;
                    <input type="submit" value=">>" />
                    <table cellpadding="0" cellspacing="0" align="center">
                        @foreach ($train as $unit)
                        <tr class="colored">
                            <td>
                                <input type="radio" name="unit_id" value="{{ $unit['uid'] }}" @if($unit['uid'] == $uid) checked @endif />
                            </td>
                            <td width=70 height=70>
                                <img src="https://www.fantasyland.ru/images/armies/{{ $unit['img'] }}" width=70 height=70 /><br />
                                <small>{{ $unit['name'] }}</small>
                            </td>
                            <td>
                                <table border=0 cellspacing=0 title='текущая тренировка - {{ $unit['w1'] }} %  за раунд - {{ $unit['percent'] }} %'>
                                    <tr>
                                        <td height=10 width={{ $unit['w1'] }} background='https://www.fantasyland.ru/images/pic/hp_green.gif'></td>
                                        <td height=10  width={{ $unit['w2'] }} background='https://www.fantasyland.ru/images/pic/hp_yellow.gif'></td>
                                        <td height=10 width={{ $unit['w3'] }}  background='https://www.fantasyland.ru/images/pic/hp_gray.gif'></td>
                                    </tr>
                                </table>
                            </td>
                            <td width=70 height=70>
                                <img src="https://www.fantasyland.ru/images/armies/{{ $unit['img2'] }}" width=70 height=70 /><br />
                                <small>{{ $unit['name2'] }}</small>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </form>
            </div>
        </div>

<!--
function addToContent(name, img, uid, name2, img2, w1, percent, w2, w3, chck,level,pid,type,level2,type2,uid2,res_info,res_info2,disa) { arenaContent += "<tr><td><INPUT type='radio' name='unit_id' value='"+uid+"' " + ((chck||uid==unit_id)?'CHECKED':'') + " title='Выбрать последователя: "+name+"' OnClick='document.formUpgrade.code.focus()' "+disa+"></td><td width=70 height=70><div id='un"+pid+uid+"' class='ArmyShow'>"+parent.no_combat._ArmyContent(type,img,"<b>"+name+"</b><br><b>Уровень: "+level+"</b>"+res_info,1,uid,pid)+"<div></td> <td><font color='#F9FBA8'>[</font>0%<font color='#F9FBA8'>]</font></td><td><table border=0 cellspacing=0 title='текущая тренировка - "+w1+" %  за раунд - "+percent+" %'><tr><td height=10 width="+w1+" background='/images/pic/hp_green.gif'></td><td height=10  width="+w2+" background='/images/pic/hp_yellow.gif'></td><td height=10 width="+w3+"  background='/images/pic/hp_gray.gif'></td></tr></table></td><td><font color='#F9FBA8'>[</font>100%<font color='#F9FBA8'>]</font></td><td width=70 height=70><div id='un"+(pid+1)+uid2+"' class='ArmyShow'>"+parent.no_combat._ArmyContent(type2,img2,"<b>"+name2+"</b><br><b>Уровень: "+level2+"</b>"+res_info2,1,uid2,(pid+1))+"</div></td></tr>"; };
-->
    </body>
</html>
