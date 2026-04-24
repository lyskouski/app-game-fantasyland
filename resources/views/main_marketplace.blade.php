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
                @if ($map)
                <br />
                @foreach ($map as $location)
                <form method="POST" action="/cgi/no_combat.php" style="margin-bottom: 8px;">
                    @csrf
                    <input type="hidden" name="locat" value="{{ $location['id'] }}" />
                    <input type="hidden" name="additional" value="0" />
                    <input type="submit" value="{{ $location['loc'] }}" style="width: 100%;" />
                </form>
                @endforeach
                @endif
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
                            <small><b>&nbsp;Торговые ряды&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <div class="tab">
                    <a href="#item" class="tablinks @if($tab == 'item') active @endif" onclick="openTab('item_table', this);return false;">Вещи</a>
                    <a href="#army" class="tablinks @if($tab == 'army') active @endif" onclick="openTab('army_table', this);return false;">Юниты</a>
                    <a href="#tent" class="tablinks @if($tab == 'tent') active @endif" onclick="openTab('tent_table', this);return false;">Палатки</a>
                </div>
                <div class="clear"></div>
                <div class="tabcontent" id="item_table" style="display: @if($tab == 'item') block @else none @endif;">
                ...
                </div>
                <div class="tabcontent" id="army_table" style="display: @if($tab == 'army') block @else none @endif;">
                ...
                </div>
                <div class="tabcontent" id="tent_table" style="display: @if($tab == 'tent') block @else none @endif;">
                @foreach ($tent as $item)
                    <p><a href="/cgi/v_trade_load_shop.php?id={{ $item['id'] }}">{!! $item['name'] !!}</a></p>
                @endforeach
                </div>
            </div>
        </div>
        <br />
    </body>
</html>