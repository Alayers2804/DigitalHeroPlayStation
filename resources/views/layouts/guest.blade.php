<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DigiPlay') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans bg-gray-900 text-white antialiased">

    <!-- Navigation Bar -->
    <nav class="bg-gray-800 p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold text-white">DigiPlay</h1>
        </div>
    </nav>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-900">
        <div class="text-center mb-8">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-white" />
            </a>
        </div>

        <!-- Login/Register Form -->
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>

</body>

</html>
