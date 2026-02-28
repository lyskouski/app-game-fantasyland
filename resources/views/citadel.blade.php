<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            html, body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
                overflow: hidden;
            }

            iframe {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                border: 0;
            }
        </style>
    </head>
    <body>
        <iframe src="https://www.citadel-liga.info/liga" allowfullscreen></iframe>
    </body>
</html>
