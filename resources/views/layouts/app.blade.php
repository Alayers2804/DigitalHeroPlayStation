<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" />
</head>

<body class="antialiased bg-gray-900 text-white">

    <!-- Navigation Bar -->
    <nav class="bg-gray-800 p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold text-white">DigiPlay</h1>
            <div>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-700">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-green-600 rounded-lg hover:bg-green-700">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-700">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto mt-10 text-center">
        <h2 class="text-4xl font-bold">Select Your Rental Date</h2>
        <p class="mt-2 text-gray-300">Click on a date to book your session</p>

        <!-- Calendar -->
        <div class="mt-6 bg-gray-800 p-6 rounded-lg shadow-lg">
            <div id="calendar"></div>
        </div>

        <!-- Pricing Section -->
        <h2 class="text-2xl font-semibold mt-8">Available Rental Services</h2>
        <ul class="mt-4 space-y-3">
            <li class="bg-gray-800 p-4 rounded-lg flex justify-between">
                <span>🎮 PS4 Rental</span>
                <strong>Rp 30,000 per session</strong>
            </li>
            <li class="bg-gray-800 p-4 rounded-lg flex justify-between">
                <span>🎮 PS5 Rental</span>
                <strong>Rp 40,000 per session</strong>
            </li>
        </ul>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                dateClick: function (info) {
                    window.location.href = "{{ route('login') }}"; // Redirect to login/register
                }
            });
            calendar.render();
        });
    </script>

</body>

</html>
