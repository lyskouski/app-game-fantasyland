<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link rel="stylesheet" href="https://www.fantasyland.ru/css/f-style.css" type="text/css" />
        <style type="text/css">TD {border-color:#555555;}</style>
    </head>
    <body>
        <br />
        <br />
        {!! $data !!}
    </body>
</html>