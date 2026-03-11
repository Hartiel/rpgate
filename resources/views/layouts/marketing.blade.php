<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head') 
    </head>
    <body class="bg-black h-screen text-white flex flex-col overflow-hidden">
        {{ $slot }}
        @fluxScripts
    </body>
</html>