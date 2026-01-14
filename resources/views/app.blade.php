<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Lab') }}</title>

        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.tsx', "resources/js/Pages/{$page['component']}.tsx"])
        @inertiaHead

        <style>
            html, body { height: 100%; width: 100%; box-sizing: border-box; font-size: 16px; }
            div#app { height: 100%; width: 100%; box-sizing: border-box; }
        </style>
    </head>
    <body class="font-sans antialiased">
        @inertia
        <div id="modal"></div>
    </body>
</html>
