<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <native:bottom-nav>
            <native:bottom-nav-item id="profile" icon="person" label="Профиль" url="/cgi/show_info.php" />
            <native:bottom-nav-item id="chat" icon="notifications" label="Сообщения" url="/ch/chout.php" />
            <native:bottom-nav-item id="home" icon="home" label="Главная" url="/cgi/no_combat.php" :active="true" />
            <native:bottom-nav-item id="forum" icon="message" label="Форум" url="/cgi/forum_rooms.php" />
            <!-- native:bottom-nav-item id="settings" icon="settings" label="Цитадель" url="/citadel" / -->
        </native:bottom-nav>

        <native:side-nav gestures-enabled="true">
            <native:side-nav-header title="Цитадель" subtitle="Сервисы к игре" icon="home" />

            <native:side-nav-item id="fight" label="Анализатор боёв" icon="analytics" url="/citadel?url=fight" />
            <native:side-nav-group heading="Карта мира" :expanded="true">
                <native:side-nav-item id="map" label="Локации Ледрака" icon="map" url="/citadel?url=map" />
                <native:side-nav-item id="caravan" label="Расписание Караванов" icon="schedule" url="/citadel?url=map/caravan" />
                <native:side-nav-item id="map" label="Локации для Атак" icon="share" url="/citadel?url=map/assault" />
                <native:side-nav-item id="ruins" label="Карта Руин" icon="dashboard" url="/citadel?url=ruins" />
            </native:side-nav-group>
            <native:side-nav-item id="quest" label="Квесты" icon="products" url="/citadel?url=quest" />
            <native:side-nav-group heading="Конкурсы" :expanded="false">
                <native:side-nav-item id="contest" label="Темы Конкурсов" icon="connections" url="/citadel?url=contest" />
                <native:side-nav-item id="tournaments" label="Авторские Турниры" icon="groups" url="/citadel?url=contest/tournaments" />
                <native:side-nav-item id="cfight" label="Бойцовский Клуб" icon="groups" url="/citadel?url=contest/fight" />
                <native:side-nav-item id="joust" label="Турнирные Сетки" icon="groups" url="/citadel?url=contest/joust" />
                <native:side-nav-item id="events" label="События" icon="inventory" url="/citadel?url=retweet/events" />
            </native:side-nav-group>
            <native:side-nav-item id="retweet" label="Новости" icon="store" url="/citadel?url=retweet" />
            <native:side-nav-item id="labs" label="Лабиринты" icon="connections" url="/citadel?url=labs" />
            <native:side-nav-group heading="Кланы" :expanded="false">
                <native:side-nav-item id="clans" label="Локатор Кланов" icon="people" url="/citadel?url=clans" />
                <native:side-nav-item id="sanctuary" label="Служители Храма" icon="people" url="/citadel?url=clans/sanctuary" />
                <native:side-nav-item id="pupil" label="Учителя" icon="people" url="/citadel?url=clans/pupil" />
            </native:side-nav-group>
            <native:side-nav-group heading="Помощь по игре" :expanded="true">
                <native:side-nav-item id="help" label="Нюансы Игры" icon="book-open" url="/citadel?url=help" />
                <native:side-nav-item id="manuscript" label="Манускрипты" icon="book-open" url="/citadel?url=help/manuscript" />
                <native:side-nav-item id="tower" label="Загадки П.Башни" icon="book-open" url="/citadel?url=help/tower" />
                <native:side-nav-item id="secrets" label="Загадки кв. Жуколовы" icon="book-open" url="/citadel?url=help/secrets" />
            </native:side-nav-group>
            <native:side-nav-group heading="Рейтинги" :expanded="false">
                <native:side-nav-item id="rating" label="Общие Рейтинги" icon="chart" url="/citadel?url=rating" />
                <native:side-nav-item id="pickers" label="Рейтинг Грибников" icon="chart" url="/citadel?url=rating/pickers" />
                <native:side-nav-item id="catchers" label="Рейтинг Жуколовов" icon="chart" url="/citadel?url=rating/catchers" />
                <native:side-nav-item id="foray" label="Набег Варварианцев" icon="chart" url="/citadel?url=rating/foray" />
                <native:side-nav-item id="invasion" label="Нашествие с Моря" icon="chart" url="/citadel?url=rating/invasion" />
            </native:side-nav-group>
            <native:side-nav-group heading="Рынок" :expanded="false">
                <native:side-nav-item id="disguise" label="Рынок" icon="shopping" url="/citadel?url=disguise" />
                <native:side-nav-item id="prices" label="Расценки Гильдий" icon="shopping" url="/citadel?url=disguise/prices" />
                <native:side-nav-item id="trend" label="Тренды Рынка" icon="shopping" url="/citadel?url=disguise/trend" />
                <native:side-nav-item id="port" label="Торговый Порт" icon="shopping" url="/citadel?url=disguise/port" />
                <native:side-nav-item id="civil" label="Расценки Магазинов" icon="shopping" url="/citadel?url=disguise/civil" />
                <native:side-nav-item id="coins" label="Цены на у.м." icon="shopping" url="/citadel?url=disguise/coins" />
                <native:side-nav-item id="effects" label="Рынок Эффектов" icon="shopping" url="/citadel?url=disguise/effects" />
            </native:side-nav-group>
            <native:side-nav-item id="services" label="Сервисы" icon="report" url="/citadel?url=services" />
            <native:side-nav-group heading="Энциклопедия" :expanded="true">
                <native:side-nav-item id="library" label="Энциклопедия" icon="inventory" url="/citadel?url=library" />
                <native:side-nav-item id="stuff" label="Вещи" icon="inventory" url="/citadel?url=library/stuff" />
                <native:side-nav-item id="crafters" label="Крафтеры" icon="inventory" url="/citadel?url=library/crafters" />
                <native:side-nav-item id="followers" label="Последователи" icon="inventory" url="/citadel?url=library/followers" />
                <native:side-nav-item id="mobs" label="Мобы" icon="inventory" url="/citadel?url=library/mobs" />
                <native:side-nav-item id="recipes" label="Рецепты" icon="inventory" url="/citadel?url=library/recipes" />
            </native:side-nav-group>

            <native:horizontal-divider />

            <native:side-nav-item
                id="help"
                label="Предложения"
                icon="help"
                url="https://www.citadel-liga.info/citadel/todo"
                open-in-browser="true"
            />
        </native:side-nav>

        <script>window.location = "/cgi/no_combat.php";</script>
    </body>
</html>