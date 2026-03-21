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
                            <small><b>&nbsp;Личные Рейтинги&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <table width="100%">
                    @foreach($ratings as $rating)
                    <tr>
                        <td colspan="3">
                            <small>{{ $rating['title'] }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20px">
                            @if($rating['image_initial'])
                            <img src="{{ $rating['image_initial'] }}" width="20" height="20" />
                            @else
                            &nbsp;
                            @endif
                        </td>
                        <td align="center" class="light">
                            {{ $rating['rating'] }}
                        </td>
                        <td style="width: 20px">
                            <img src="{{ $rating['image_final'] }}" width="20" height="20" />
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <br />
    </body>
</html>