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
                @if(isset($title_tent))
                <form method="GET" action="/cgi/no_combat.php" style="margin-bottom: 8px;">
                    @csrf
                    <input type="submit" value="к Торговым рядам" style="width: 100%;" />
                </form>
                @endif
                @if (isset($hasRoad) && $hasRoad)
                    <br />
                    <form method="GET" action="/cgi/map.php" style="margin-bottom: 8px;">
                        @csrf
                        <input type="submit" value="Покинуть локацию" style="width: 100%;" />
                    </form>
                @endif
            </div>
        </div>
        <br />
        @if (isset($buy))
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;{{ $title_tent ?? 'Магазин' }}&nbsp;</b></small>
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
                    <a href="#buy" class="tablinks @if(isset($tab) && $tab == 'buy' || !isset($sell)) active @endif" onclick="openTab('buy_table', this);return false;">Покупка</a>
                    @if (isset($sell))
                    <a href="#sell" class="tablinks @if(isset($tab) && $tab == 'sell') active @endif" onclick="openTab('sell_table', this);return false;">Продажа</a>
                    @endif
                </div>
                <div class="clear"></div>
                <table class="tabcontent" id="buy_table" style="display: @if(isset($tab) && $tab == 'buy' || !isset($sell)) table @else none @endif;">
                @foreach ($buy as $i => $item)
                <tr class="{{ $i % 2 == 0 ? 'light' : '' }}">
                    <td>
                        <img src="https://www.fantasyland.ru/{{ $item['img'] }}" />
                    </td>
                    <td>
                        <small>({{ $item['count'] }}) {{ $item['title'] }}</small><br />
                        <span data-cost="{{ $item['cost'] }}" id="b{{ $item['good_id'] }}">{{ $item['cost'] }}</span>
                        <img src="https://www.fantasyland.ru/images/miscellaneous/money.gif" align="absmiddle" />
                    </td>
                    <td>
                        <form method="POST" action="/cgi/buy.php">
                            @csrf
                            <input type="hidden" name="good_id" value="{{ $item['good_id'] }}" />
                            <input type="hidden" name="shp_id" value="{{ $item['shp_id'] ?? '' }}" />
                            <input type="hidden" name="good_type" value="{{ $item['good_type'] ?? '' }}" />
                            <input type="hidden" name="price_quest" value="{{ $item['price_quest'] ?? '' }}" />
                            <input type="hidden" name="capCode" value="{{ $item['capCode'] ?? '' }}" />
                            <center>
                                <input type="submit" value="Купить" /><br />
                                <input type="text" name="number" value="{{ $item['number'] ?? 1 }}" size="3" onkeyup="updateCost('b{{ $item['good_id'] }}', this.value)" />
                            </center>
                        </form>
                    </td>
                </tr>
                @endforeach
                </table>
                @if (isset($sell))
                <table class="tabcontent" id="sell_table" style="display: @if(isset($tab) && $tab == 'sell') table @else none @endif;">
                @foreach ($sell as $i => $item)
                <tr class="{{ $i % 2 == 0 ? 'light' : '' }}">
                    <td>
                        <img src="https://www.fantasyland.ru/{{ $item['img'] }}" />
                    </td>
                    <td>
                        <small>({{ $item['count'] }}) {{ $item['title'] }}</small><br />
                        <span data-cost="{{ $item['cost'] }}" id="s{{ $item['good_id'] }}">{{ $item['cost'] }}</span>
                        <img src="https://www.fantasyland.ru/images/miscellaneous/money.gif" align="absmiddle" />
                    </td>
                    <td>
                        <form method="POST" action="/cgi/sell_good_to_shop.php">
                            @csrf
                            <input type="hidden" name="good_id" value="{{ $item['good_id'] }}" />
                            <input type="hidden" name="shp_id" value="{{ $item['shp_id'] ?? '' }}" />
                            <center>
                                <input type="submit" value="Продать" /><br />
                                <input type="text" name="number" value="{{ $item['number'] ?? 1 }}" size="3" onkeyup="updateCost('s{{ $item['good_id'] }}', this.value)" />
                            </center>
                        </form>
                    </td>
                </tr>
                @endforeach
                </table>
                @endif
            </div>
        </div>
        @endif
    </body>
</html>