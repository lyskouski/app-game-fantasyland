<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Лига Героев</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/craft.css', 'resources/css/index.css', 'resources/js/ping.js'])
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
                <img src="https://www.fantasyland.ru/{{ $image }}" width="100%" />
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
                            <small><b>&nbsp;Локации для перехода&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                @foreach ($place as $location)
                <form method="POST" action="/cgi/no_combat.php" style="margin-bottom: 8px;">
                    @csrf
                    <input type="hidden" name="place_regime" value="{{ $location['id'] }}" />
                    <input type="hidden" name="addval" value=0 />
                    <input type="hidden" name="addval1" value=0 />
                    <input type="submit" value="{{ $location['loc'] }}" style="width: 100%;" />
                </form>
                @endforeach
                @foreach ($map as $location)
                <form method="POST" action="/cgi/no_combat.php" style="margin-bottom: 8px;">
                    @csrf
                    <input type="hidden" name="locat" value="{{ $location['id'] }}" />
                    <input type="hidden" name="additional" value="0" />
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
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15" />
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Крафт&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <p>Доступные рецепты на все уровни:</p>
                @foreach ($craft as $type)
                <form method="POST" action="/cgi/no_combat.php" style="margin-bottom: 8px;">
                    @csrf
                    <input type="hidden" name="place_regime" value="{{ $type['id'] }}" />
                    <input type="hidden" name="addval" value=0 />
                    <input type="hidden" name="addval1" value=0 />
                    <input type="submit" value="{{ $type['title'] }}" style="display:block;float:left;padding:2px 4px;margin:6px;" />
                </form>
                @endforeach
                <div style="clear: both;">&nbsp;</div>
                <div id="receipt" style="display:none;">
                    <table width="100%">
                        <tr>
                            <td valign="top">
                                <form method="GET" action="/cgi/craft_favorite_ref.php">
                                    <input type="hidden" name="id" id="receipt_id" value="" />
                                    <input type="hidden" name="checked" value="1" />
                                    <input type="submit" value="В избранное" />
                                </form>
                                <div class="item" id="receipt_item"></div>
                                <div style="clear: both;">&nbsp;</div>
                                <strong>Рецепт:</strong><br />
                                <small id="receipt_value"></small>
                            </td>
                            <td valign="top">
                                <form method="POST" action="/cgi/work_start.php">
                                    @csrf
                                    <input type="hidden" name="item_id" id="item_id" value="" />
                                    <input type="hidden" name="enchant" value="0" />
                                    <input type="hidden" name="enchant_length" value="1" />
                                    <table>
                                        <tr>
                                            <td>Время:</td>
                                            <td><input type="text" size="4" id="receipt_time" disabled /></td>
                                        </tr>
                                        <tr>
                                            <td>Кол-во:</td>
                                            <td><input type="text" name="count" size="4" value="" /></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <img src="{!! $captcha !!}" width="90" height="40" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <input type="text" name="value" size="6" autocomplete="off" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align: right">
                                                <br />
                                                <input type="submit" value="Сделать" />
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <br />
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15" />
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Рецепты&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                @foreach ($recipes as $recipe)
                <div class="item" data-id="{{ $recipe['id'] }}" data-receipt="{{ $recipe['receipt'] }}" data-time="{{ $recipe['time'] }}">
                    <img title="{{ $recipe['title'] }}" src="https://www.fantasyland.ru/{{ $recipe['src'] }}" />
                    <strong>{{ $recipe['count'] }}</strong>
                </div>
                @endforeach
                <div style="clear: both;">&nbsp;</div>
            </div>
        </div>
        <br />
        <script>
            document.querySelectorAll('.item').forEach(item => {
                item.addEventListener('click', () => {
                    document.getElementById('receipt').style.display = 'block';
                    document.getElementById('receipt_item').innerHTML = item.innerHTML;
                    const receipt = item.getAttribute('data-receipt');
                    document.getElementById('receipt_value').innerHTML = receipt;
                    const time = item.getAttribute('data-time');
                    document.getElementById('receipt_time').value = time;
                    document.getElementById('receipt_id').value = item.getAttribute('data-id');
                    document.getElementById('item_id').value = item.getAttribute('data-id');
                });
            });
        </script>
    </body>
</html>