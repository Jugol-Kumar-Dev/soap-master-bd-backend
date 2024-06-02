<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'App') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">




        <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
        <link rel="manifest" href="./favicon/site.webmanifest">


        <meta property="og:image" content="./favicon/favicon-32x32.png" />
        <meta property="og:title" content="Australian Medical Council Exam (AMC) Preparation" />
        <meta property="og:description" content="AMCPaedia is a brand that focuses on your needs and goals becoming your true partner in progress, We are launching a full preparation course from January, 2024."/>
        <meta property="og:type" content="website" />
        <meta property="og:image:width" content="200"/>
        <meta property="og:image:height" content="300"/>

        <meta name="twitter:title" content="Australian Medical Council Exam (AMC) Preparation" />
        <meta name="twitter:image" content="./public/logo.png">
        <meta name="twitter:description" content="AMCPaedia is a brand that focuses on your needs and goals becoming your true partner in progress, We are launching a full preparation course from January, 2024.">






        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
