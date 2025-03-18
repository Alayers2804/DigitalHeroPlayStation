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
            <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-400 hover:text-blue-300 transition">
                🎮 DigiPlay
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="min-h-screen flex flex-col items-center justify-center px-6 py-12">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-white">Welcome to DigiPlay</h1>
            <p class="text-gray-400 mt-2">Log in to book your gaming session now!</p>
        </div>

        <!-- Authentication Box -->
        <div class="w-full max-w-md bg-gray-800 p-8 rounded-lg shadow-lg">
            {{ $slot }}
        </div>
    </div>

</body>

</html>
