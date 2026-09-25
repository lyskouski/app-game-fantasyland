<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Лига Героев</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/index.css', 'resources/js/ping.js'])
    </head>
    <body>
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

        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Опции&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                @foreach ($map as $location)
                <form method="GET" action="/cgi/arena.php" style="margin-bottom: 8px;">
                    @csrf
                    <input type="hidden" name="g" value="{{ $location['id'] }}" />
                    <input type="submit" @if($location['id'] == $current) disabled @endif value="{{ $location['loc'] }}" style="width: 100%;" />
                </form>
                @endforeach
                <br />
                @foreach ($place as $location)
                <form method="POST" action="/cgi/no_combat.php" style="margin-bottom: 8px;">
                    @csrf
                    <input type="hidden" name="locat" value="{{ $location['id'] }}" />
                    <input type="hidden" name="additional" value=0 />
                    <input type="submit" value="{{ $location['loc'] }}" style="width: 100%;" />
                </form>
                @endforeach
            </div>
        </div>

        <div class="main">
            <div class="main_top">
                <table cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_l.gif" width="30" height="15">
                        </td>
                        <td valign="top" class="cell_title">
                            <small><b>&nbsp;Тренировочный бой&nbsp;</b></small>
                        </td>
                        <td>
                            <img src="https://www.fantasyland.ru/images/buttons/tab_r.gif" width="30" height="15" />
                        </td>
                    </tr>
                </table>
                <br />
            </div>
            <div class="main_middle">
                <p><small>{{ $hp_description }}</small></p>
                <label class="bar_row">
                    <img width="20" height="20" src="https://www.fantasyland.ru/images/miscellaneous/hp.gif" title="Здоровье" />
                    <progress class="bar" value="{{ $hp_current }}" max="{{ $hp_full }}"></progress>
                    <span class="bar_row__value">[<span class="h_actual">{{ $hp_current }}</span>/<span class="h_full">{{ $hp_full }}</span>]</span>
                </label>
                <form id="arena_state" method="GET" action="/cgi/arena.php">
                    <input type="hidden" name="g" value="{{ $current }}" />
                </form>
            </div>
        </div>
        <br />
        <script>
            (function() {
                const bar = document.querySelector('.bar_row .bar');
                const actual = document.querySelector('.bar_row .h_actual');
                const full = {{ $hp_full }};
                const speed = {{ $hp_speed }};
                const sm = {{ $hp_current }};
                const t0 = Date.now() - 3000;
                const tick = () => {
                    const current = Math.min(full, Math.round(sm + speed * (Date.now() - t0) / 60000));
                    if (current >= full) {
                        bar.value = full;
                        actual.innerHTML = full;
                        document.getElementById('arena_state').submit();
                        return;
                    }
                    bar.value = current;
                    actual.innerHTML = current;
                    setTimeout(tick, 1000);
                };
                setTimeout(tick, 1000);
            })();
        </script>
    </body>
</html>
