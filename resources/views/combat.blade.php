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
            <details name="tech-specs">
                <summary>
                    Имя пользователя<br />
                    <label>
                        <!-- (width >= 67) 'hp_green'
                             (width <= 20) 'hp_red'
                             (width < 67 && width > 20) 'hp_yellow' -->
                        XP: <progress class="bar" value="20" max="100"></progress>
                    </label>
                </summary>
                <ul>
                    <li>...</li>
                    <li>...</li>
                    <li>...</li>
                </ul>
            </details>
        </div>
    </body>
</html>