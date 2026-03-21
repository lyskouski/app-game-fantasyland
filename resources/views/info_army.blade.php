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
                            <small><b>&nbsp;Информация об армиях&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15">
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                @foreach($army as $i => $item)
                <table class="{{ $i % 2 == 0 ? 'light' : '' }}" width="100%" colspacing="0" cellpadding="0">
                    <tr>
                        <td style="width: 80px" valign="top">
                            <strong class="main_middle__count">{{ $item['count'] }}</strong>
                            <img src="{{ $item['image'] }}" width="70" height="70" />
                        </td>
                        <td valign="top">
                            <input style="position:relative;float:right;" class="army_selection" type="checkbox" data-id="{{ $item['id'] }}" @if($item['selected']) checked @endif />
                            <small>
                                <strong>{!! $item['name'] !!}</strong><br />
                                {{ $item['lvl'] }}<br />
                            </small>
                            <small class="tiny">{!! $item['effects'] !!}</small>
                        </td>
                    </tr>
                </table>
                @endforeach
                <script>
                    document.querySelectorAll('.army_selection').forEach(el => {
                        el.addEventListener('change', function() {
                            fetch('/cgi/army_needcombat_ref.php?id=' + this.dataset.id, {
                                method: 'GET',
                                headers: {
                                    'Content-Type': 'application/json'
                                }
                            });
                        });
                    });
                </script>
            </div>
        </div>
        <br />
    </body>
</html>