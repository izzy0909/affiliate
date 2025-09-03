<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <title>Affiliate Distance</title>
    </head>
    <body>
        @livewireScripts

        <livewire:affiliate-file-upload />
    </body>
</html>