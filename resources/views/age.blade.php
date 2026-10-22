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
        <div style="min-height:30%">&nbsp;</div>
        <div class="main">
            <div class="main_top">
                <div class="main_top__header">Год рождения</div>
            </div>
            <div class="main_middle">
                <form method="POST" action="/age">
                    @csrf
                    <p>Укажите ваш год рождения. Он хранится только на этом устройстве.</p>
                    <p>
                        <label for="birth_year">Год рождения</label><br />
                        <select id="birth_year" name="birth_year" required>
                            <option value="" selected disabled>—</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </p>
                    @error('birth_year')
                        <p><font color="#FF0000">Укажите год рождения</font></p>
                    @enderror
                    <p>
                        <input type="submit" value="Продолжить" />
                    </p>
                </form>
            </div>
        </div>
    </div>
    </body>
</html>
