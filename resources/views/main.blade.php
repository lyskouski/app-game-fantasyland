<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <br />
        <br />
        <native:bottom-nav>
            <native:bottom-nav-item id="profile" icon="person" label="Профиль" url="/cgi/show_info.php" />
            <native:bottom-nav-item id="chat" icon="chat" label="Чат" url="/ch/chout.php" badge="3" />
            <native:bottom-nav-item id="home" icon="home" label="Главная" url="/cgi/no_combat.php" :active="true" />
            <native:bottom-nav-item id="contacts" icon="contacts" label="Форум" url="/cgi/forum_rooms.php" />
            <native:bottom-nav-item id="settings" icon="settings" label="Цитадель" url="/citadel" />
        </native:bottom-nav>
    </body>
</html>