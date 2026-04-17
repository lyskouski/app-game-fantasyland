<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Лига Героев</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/index.css', 'resources/js/info_runes.js'])
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
                            <small><b>&nbsp;Руны&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <p>
                    <b>Купить эффект:</b>
                    <select id="type" onChange="updateDescription();">
                        <option value=5 selected>Помощника</option>
                        <option value=7>Скрытности</option>
                        <option value=8>Здоровья</option>
                        <option value=9>Марафонщика</option>
                        <option value=2>Обучаемости</option>
                        <option value=1>Добытчика</option>
                        <option value=3>Работника</option>
                        <option value=4>Наставника</option>
                    </select>
                </p>
                <p><b>Действие:</b> <span id="desc">авто перезапуск добычи, крафта, тренинга</span></p>
                <p>
                    <b>На срок: </b>
                    <select id="long" onChange="setPrice();">
                        <option value=0>2 часа</option>
                        <option value=1>2 дня</option>
                        <option value=2 selected>1 неделя</option>
                        <option value=3>1 месяц</option>
                    </select>
                </p>
                <p>
                    <b>Итоговая стоимость:</b>
                    <span id="price">-</span>
                    <img src='https://www.fantasyland.ru/images/miscellaneous/quest.gif' title="Урановые Монеты" />
                </p>
                <p>
                    <b>Доступно:</b>
                    <span>{{ $money }}</span>
                    <img src='https://www.fantasyland.ru/images/miscellaneous/quest.gif' title='Урановые Монеты' />
                </p>
                <form method="POST" action="/cgi/add_um_effect.php">
                    @csrf
                    <input type="hidden" name="type" id="type_input" value="5" />
                    <input type="hidden" name="long" id="long_input" value="2" />
                    <button type="submit" class="button">Купить</button>
                </form>
                <br /><br />
                <p>Вы можете подарить выбранную руну указанному персонажу:</p>
                <form method="POST" action="/cgi/add_um_effect.php">
                    @csrf
                    <input type="hidden" name="type" id="gift_type_input" value="5" />
                    <input type="hidden" name="long" id="gift_long_input" value="2" />
                    <input type="text" name="whom" placeholder="Имя персонажа" required /><br /><br />
                    <button type="submit" class="button">Подарить</button>
                </form>
            </div>
        </div>
        <br />
    </body>
</html>