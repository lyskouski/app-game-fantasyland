<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Лига Героев</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/index.css', 'resources/js/fight.js', 'resources/js/timer.js'])
</head>

<body>
    <button type="button" class="combat_log_toggle" onclick="toggleCombatLog()">☰</button>
    <div id="combat_log_drawer" class="combat_log_drawer">
        <div id="combat_log_content"></div>
    </div>
    <div class="main">
        <div class="main_top">
            <table cellpadding="0" cellspacing="0" align="center">
                <tr>
                    <td>
                        <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                    </td>
                    <td valign="top" class="cell_title">
                        <small><b>&nbsp;Оппоненты в бою&nbsp;</b></small>
                    </td>
                    <td>
                        <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                    </td>
                </tr>
            </table>
            <br />
        </div>
        <div class="main_middle">
            <template id="fight_template">
                <details name="tech-specs">
                    <summary class="fight_summary">
                        <div class="fight_army">
                            <div class="fight_army_scroll">
                                <img src="https://www.fantasyland.ru/cgi/1x1_tr.gif" width="30px" height="30px" />
                            </div>
                            <div class="fight_army_follower">
                                <img src="https://www.fantasyland.ru/cgi/1x1_tr.gif" width="74px" height="74px" />
                            </div>
                        </div>
                        [<span class="level">?</span>]&nbsp;
                        <span class="name">Имя пользователя</span>&nbsp;
                        <image width="10" height="10" class="gender" src="https://www.fantasyland.ru/images/miscellaneous/info_?.gif" /><br />
                        <label class="bar_row">
                            <img width="20" height="20" src="https://www.fantasyland.ru/images/miscellaneous/hp.gif" title="Здоровье" />
                            <progress class="bar" value="20" max="100"></progress>
                            <span class="bar_row__value">[<span class="h_actual">?</span>/<span class="h_full">?</span>]</span>
                        </label>
                    </summary>
                    <table>
                        <tr>
                            <td>
                                <image width="20" height="20" src='https://www.fantasyland.ru/images/miscellaneous/attack_d.gif' title='Атака Драконов'>
                            </td>
                            <td>
                                <image width="20" height="20" src='https://www.fantasyland.ru/images/miscellaneous/defence_d.gif' title='Защита от Драконов'>
                            </td>
                            <td nowrap><span class="drak">?</span>&nbsp;</td>
                            <td>
                                <image width="20" height="20" src='https://www.fantasyland.ru/images/miscellaneous/attack_c.gif' title='Атака Хаоса'></td>
                            <td>
                                <image width="20" height="20" src='https://www.fantasyland.ru/images/miscellaneous/defence_c.gif' title='Защита от Хаоса'>
                            </td>
                            <td nowrap><span class="haos">?</span>&nbsp;</td>
                            <td rowspan="4">
                                <div class="eff"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <image width="20" height="20" src='https://www.fantasyland.ru/images/miscellaneous/attack_k.gif' title='Атака Рыцарей'>
                            </td>
                            <td>
                                <image width="20" height="20" src='https://www.fantasyland.ru/images/miscellaneous/defence_k.gif' title='Защита от Рыцарей'>
                            </td>
                            <td nowrap><span class="ric">?</span>&nbsp;</td>
                            <td>
                                <image width="20" height="20" src='https://www.fantasyland.ru/images/miscellaneous/attack_h.gif' title='Атака Света'>
                            </td>
                            <td>
                                <image width="20" height="20" src='https://www.fantasyland.ru/images/miscellaneous/defence_h.gif' title='Защита от Света'>
                            </td>
                            <td nowrap><span class="svet">?</span>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <image width="20" height="20" src='https://www.fantasyland.ru/images/miscellaneous/attack_l.gif' title='Атака Дам'>
                            </td>
                            <td>
                                <image width="20" height="20" src='https://www.fantasyland.ru/images/miscellaneous/defence_l.gif' title='Защита от Дам'>
                            </td>
                            <td nowrap><span class="dam">?</span>&nbsp;</td>
                            <td>
                                <image width="20" height="20" src='https://www.fantasyland.ru/images/miscellaneous/attack_s.gif' title='Атака Колдовства'>

                            </td>
                            <td>
                                <image width="20" height="20" src='https://www.fantasyland.ru/images/miscellaneous/defence_s.gif' title='Защита от Колдовства'>
                            </td>
                            <td nowrap><span class="kold">?</span>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <image width="20" height="20" src='https://www.fantasyland.ru/images/miscellaneous/attack_a.gif' title='Атака Астрала'>

                            </td>
                            <td>
                                <image width="20" height="20" src='https://www.fantasyland.ru/images/miscellaneous/defence_a.gif' title='Защита от Астрала'>
                            </td>
                            <td nowrap><span class="astrl">?</span></td>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="7">
                                <table>
                                    <tr>
                                        <td class="luckSh1" style="display:none">
                                            <image width=20 height=20 src='https://www.fantasyland.ru/images/miscellaneous/luck.gif' title='Удача'>
                                        </td>
                                        <td nowrap class="luckSh2" style="display:none"><span class="luck">?</span></td>

                                        <td class="regSh1" style="display:none">
                                            <image width=20 height=20 src='https://www.fantasyland.ru/images/miscellaneous/regen_hp.gif' title='Восстановление Жизни'>
                                        </td>
                                        <td nowrap class="regSh2" style="display:none"><span class="reg">?</span></td>

                                        <td class="learnSh1" style="display:none">
                                            <image width=20 height=20 src='https://www.fantasyland.ru/images/miscellaneous/learn.gif' title='Обучаемость'>
                                        </td>
                                        <td nowrap class="learnSh2" style="display:none"><span class="learn">?</span></td>

                                        <td class="concSh1" style="display:none">
                                            <image width=20 height=20 src='https://www.fantasyland.ru/images/miscellaneous/conc.gif' title='Концентрация'>
                                        </td>
                                        <td nowrap class="concSh2" style="display:none"><span class="conc">?</span></td>

                                        <td class="ppSh1" style="display:none">
                                            <image width=20 height=20 src='https://www.fantasyland.ru/images/miscellaneous/pp.gif' title='Защита от Яда'>
                                        </td>
                                        <td nowrap class="ppSh2" style="display:none"><span class="pp">?</span></td>

                                        <td class="m_protSh1" style="display:none">
                                            <image width=20 height=20 src='https://www.fantasyland.ru/images/miscellaneous/m_prot.gif' title='Сила Эффектов'>
                                        </td>
                                        <td nowrap class="m_protSh2" style="display:none"><span class="m_prot">?</span></td>

                                        <td class="pnSh1" style="display:none">
                                            <image width=20 height=20 src='https://www.fantasyland.ru/images/miscellaneous/pn.gif' title='Защита от Нейтральных Заклятий'>
                                        </td>
                                        <td nowrap class="pnSh2" style="display:none"><span class="pn">?</span></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </details>
            </template>
            <div id="fight_opponents"></div>
            <hr />
            <center>
                <strong id="timer" data-seconds="180" data-infinite="1">Загрузка...</strong>
            </center>
            <hr />
            <div id="fight_enemies"></div>
        </div>
    </div>

    <div class="main main--light">
        <div class="main_top">
            <table cellpadding="0" cellspacing="0" align="center">
                <tr>
                    <td>
                        <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                    </td>
                    <td valign="top" class="cell_title">
                        <small><b>&nbsp;Свитки и последователи&nbsp;</b></small>
                    </td>
                    <td>
                        <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                    </td>
                </tr>
            </table>
            <br />
        </div>
        <div class="main_middle">
            @foreach($scrolls as $i => $scroll)
            <p class="item {{ $i % 2 == 0 ? 'light' : '' }} scroll_item">
                <button style="position:relative;float:right;" type="button" onclick="useScroll('{{ $scroll['id'] }}')">Выбрать</button>
                <img src="https://www.fantasyland.ru{{ $scroll['image'] }}" width="20" height="20" align="absmiddle" />&nbsp;
                <small>{!! $scroll['title'] !!}</small>
            </p>
            @endforeach
            <p>
                <button type="button" onclick="showArmy(0)">Bce</button>
                <button type="button" onclick="showArmy(1)">Драконы</button>
                <button type="button" onclick="showArmy(2)">Рыцари</button>
                <button type="button" onclick="showArmy(3)">Дамы</button>
            </p>
            @foreach($army as $i => $item)
            <table class="{{ $i % 2 == 0 ? 'light' : '' }} army-type army-type_{{ $item['type'] }}" id="army_{{ $item['id'] }}" width="100%" colspacing="0" cellpadding="0">
                <tr>
                    <td style="width: 80px" valign="top">
                        <strong class="main_middle__count">{{ $item['count'] }}</strong>
                        <a href="/cgi/army_desc.php?id={{ $item['id'] }}">
                            <img src="https://www.fantasyland.ru/images/armies/{{ $item['image'] }}" width="70" height="70" />
                        </a>
                    </td>
                    <td valign="top">
                        <button style="position:relative;float:right;" type="button" onclick="useArmy('{{ $item['id'] }}')">Выбрать</button>
                        <small class="tiny">{!! $item['descr'] !!}</small>
                    </td>
                </tr>
            </table>
            @endforeach
        </div>
    </div>
    <br />
</body>

</html>