<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>The People of Kings Hope</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])                
        @endif
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            [x-cloak] {display: none !important;}
        </style>
    </head>
    <body>
        <x-header heading="Connecting at Kings Hope Church"/>
        <main class="px-2 my-5 sm:p-4 max-w-5xl mx-auto w-full" x-data="form_handler({{ Js::from($apiResponse['data'])  }}, {{ Js::from($apiResponse['pagination']) }})">
            <x-search-bar />
            <x-results-table />
            <x-footer/>
        </main>
    </body>
</html>
