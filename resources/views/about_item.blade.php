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
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15" />
                        </td>
                        <td valign="top" class="cell_title">
                            <small>&nbsp;<b>{{ $name }}</b>&nbsp;[Ур. {{ $lvl }}]&nbsp;</small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <img src="{{ $image }}" class="location" />
                @if($description)
                <small>{{ $description }}</small>
                @endif
                <div class="clear"></div>
                <b>Цена:</b> {{ $cost }} {{ $cost_type}}<br />
                <table width="100%">
                @if($properties)
                    <tr class="colored">
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    @foreach($properties as $property)
                    <tr class="colored">
                        <td width="16" align="center" valign="middle">
                            @if($property['image'])
                            <img src="{{ $property['image'] }}" width="12" height="12" />
                            @else
                            &nbsp;
                            @endif
                        </td>
                        <td>{{ $property['property'] }}</td>
                        <td align="right">{{ $property['value'] }}</td>
                    </tr>
                    @endforeach
                @endif
                @if($required)
                    <tr class="colored">
                        <td colspan="3"><b>Требования:</b></td>
                    </tr>
                    @foreach($required as $property)
                    <tr class="colored">
                        <td width="16" align="center" valign="middle">
                            @if($property['image'])
                            <img src="{{ $property['image'] }}" width="12" height="12" />
                            @else
                            &nbsp;
                            @endif
                        </td>
                        <td>{{ $property['property'] }}</td>
                        <td align="right">{{ $property['value'] }}</td>
                    </tr>
                    @endforeach
                @endif
                </table>
            </div>
        </div>
        <br />
        @if($made)
            @foreach($made as $group)
            <div class="main">
                <div class="main_top">
                    <table cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td>
                                <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15" />
                            </td>
                            <td valign="top" class="cell_title">
                                <small><b>&nbsp;{{ $group[0]['who_can'] }}&nbsp;</b></small>
                            </td>
                            <td>
                                <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                            </td>
                        </tr>
                    </table>
                    <br />
                </div>
                <div class="main_middle">
                    <table width="100%">
                    @foreach($group as $item)
                        @if($item['pro_type'] === 'items')
                        <tr class="colored">
                            <td width="36" align="center" valign="middle">
                                @if($item['image'])
                                <a href="/cgi/item_desc.php?id={{ $item['id'] }}">
                                    <img src="{{ $item['image'] }}" width="32" height="32" />
                                </a>
                                @else
                                &nbsp;
                                @endif
                            </td>
                            <td>{{ $item['type'] }}</td>
                            <td align="right">{{ $item['value'] }}</td>
                        </tr>
                        @else
                        <tr class="colored">
                            <td colspan="3">
                                <b>{{ $item['type'] }}:</b> {{ $item['value'] }}
                            </td>
                        </tr>
                        @endif
                    @endforeach
                    </table>
                </div>
            </div>
            <br />
            @endforeach
        @endif
        <a class="back" href="#back" onclick="history.back();">Вернуться назад</a>
    </body>
</html>
