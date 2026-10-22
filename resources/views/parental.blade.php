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
    <body><div class="body">
        <div style="min-height:20%">&nbsp;</div>
        <div class="main">
            <div class="main_top">
                <div class="main_top__header">Родительский контроль</div>
            </div>
            <div class="main_middle">
                @if(session('status'))
                    <p><b>{{ session('status') }}</b></p>
                @endif
                @if($errors->any())
                    <p><font color="#FF0000">{{ $errors->first() }}</font></p>
                @endif
                <form method="POST" action="/parental">
                    @csrf
                    @if($hasPin)
                        <p>
                            <label for="pin">Текущий PIN</label><br />
                            <input type="password" id="pin" name="pin" inputmode="numeric" maxlength="4" required />
                        </p>
                    @endif
                    <p>
                        <label>
                            <input type="checkbox" name="social_lock" value="1" @checked($locked) />
                            Отключить чат, почту и форум
                        </label>
                    </p>
                    <p>
                        <label for="new_pin">{{ $hasPin ? 'Новый PIN (необязательно)' : 'Задать PIN (4 цифры)' }}</label><br />
                        <input type="password" id="new_pin" name="new_pin" inputmode="numeric" maxlength="4" />
                    </p>
                    <p>
                        <label for="new_pin_confirmation">Повторите PIN</label><br />
                        <input type="password" id="new_pin_confirmation" name="new_pin_confirmation" inputmode="numeric" maxlength="4" />
                    </p>
                    @if($hasPin)
                        <p>
                            <label>
                                <input type="checkbox" name="remove_pin" value="1" />
                                Удалить PIN и снять ограничения
                            </label>
                        </p>
                    @endif
                    <p>
                        <input type="submit" value="Сохранить" />
                    </p>
                </form>
            </div>
        </div>
    </div>
    </body>
</html>
