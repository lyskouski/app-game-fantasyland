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
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/miscellaneous/title_left.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;{{ $data['name'] }} [L{{ $data['level'] }}]&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/miscellaneous/title_right.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <img src="{{ $data['image'] }}" width="100%" />
                <br />
                <table width="100%" colspacing="0" cellpadding="0">
                    <tr>
                        <td style="width: 20px">
                            <img src="https://www.fantasyland.ru//images/miscellaneous/hp.gif" width="15" height="15" title="Здоровье" />
                        </td>
                        <td>
                            <small>Здоровье</small>
                        </td>
                        <td align="right">
                            <small><b>{{ $data['hp'] }}</b></small>
                        </td>
                    </tr>
                    @foreach($data['effects'] as $i => $effect)
                    <tr class="{{ $i % 2 == 0 ? 'light' : '' }}">
                        <td style="width: 20px">
                            <img src="{{ $effect['image'] }}" width="15" height="15" title="{{ $effect['title'] }}" />
                        </td>
                        <td>
                            <small>{{ $effect['title'] }}</small>
                        </td>
                        <td align="right">
                            <small><b>{{ $effect['value'] }}</b></small>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/miscellaneous/title_left.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Активность&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/miscellaneous/title_right.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <b>Активность:</b> {{ $data['activity'] }}<br />
                <b>Статус:</b> {{ $data['aggressive'] }}<br />
                <br />

                @if(count($data['habitat']))
                <b>Место обитания:</b>
                <table width="100%">
                    @foreach($data['habitat'] as $i => $place)
                    <tr class="{{ $i % 2 == 0 ? 'light' : '' }}">
                        <td>{{ $place['location'] }}</td>
                        <td>{{ $place['level'] }}</td>
                    </tr>
                    @endforeach
                </table>
                <br />
                @endif

                @if($data['description'])
                <p>
                    <b>Описание:</b>
                    {{ $data['description'] }}
                </p>
                @endif
            </div>
        </div>

        @if(count($data['drop']))
        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/miscellaneous/title_left.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Дроп&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/miscellaneous/title_right.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <table width="100%">
                @foreach($data['drop'] as $i => $item)
                    <tr class="{{ $i % 2 == 0 ? 'light' : '' }}">
                        <td width="25"><img width="20" height="20" src="{{ $item['image'] }}" title="{{ $item['name'] }}" /></td>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['count'] }}</td>
                        <td>{{ $item['chance'] }}%</td>
                    </tr>
                @endforeach
                </table>
                @if($data['profit'])
                <br />
                <b>Средняя прибыль:</b> {{ $data['profit'] }}
                @endif
            </div>
        </div>
        @endif
    </body>
</html>
