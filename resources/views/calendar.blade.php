<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Weeks</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body>
        <div class="container-fluid" style="padding: 2vh;">
            <div id="calendar"></div>
        </div>
    </body>
</html>
