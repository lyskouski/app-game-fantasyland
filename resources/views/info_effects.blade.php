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
                            <small><b>&nbsp;Награды&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <table width="100%" style="table-layout: fixed;">
                    @foreach($awards as $award)
                    <tr>
                        <td style="width: 20px; flex-shrink: 0;">
                            <img src="{{ $award['image'] }}" width="20" height="20" />
                        </td>
                        <td class="nowrap" style="flex-shrink: 0; overflow: hidden;">
                            <small><b>{{ $award['title'] }}</b></small>
                        </td>
                        <td class="nowrap" style="width: 70px; flex-shrink: 0; overflow: hidden;" align="right">
                            <small>{!! $award['effect'] !!}</small>
                        </td>
                    </tr>
                    @endforeach
                </table>
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
                            <small><b>&nbsp;Эффекты&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <table width="100%" style="table-layout: fixed;">
                    @foreach($effects as $effect)
                    <tr>
                        <td style="width: 20px; flex-shrink: 0;">
                            <img src="{{ $effect['image'] }}" width="20" height="20" />
                        </td>
                        <td class="nowrap" style="flex-shrink: 0; overflow: hidden;">
                            <small><b>{{ $effect['title'] }}</b></small>
                            @if ($effect['time'])
                            <br /><small>{{ $effect['time'] }}</small>
                            @endif
                        </td>
                        <td class="nowrap" style="width: 70px; flex-shrink: 0; overflow: hidden;" align="right">
                            <small>{!! $effect['effect'] !!}</small>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <br />
    </body>
</html>