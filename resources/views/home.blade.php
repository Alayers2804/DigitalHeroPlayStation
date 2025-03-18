<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" />

    <style>
        /* Improve Navigation Active State */
        .nav-link {
            @apply px-4 py-2 rounded-md transition duration-300;
        }
        .nav-link-active {
            @apply bg-blue-500 text-white font-semibold shadow-md;
        }
        .nav-link:hover {
            @apply bg-blue-400 text-white;
        }
    </style>
</head>

<body class="antialiased bg-gray-900 text-white">

    <!-- Navigation Bar -->
    <nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-3xl font-extrabold text-blue-400 hover:text-blue-300 transition">
                    🎮 DigiPlay
                </a>

                <!-- Navigation Links -->
                <div class="hidden sm:flex space-x-6">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}">
                        {{ __('Dashboard') }}
                    </a>

                    <a href="{{ route('bookings.index') }}" class="nav-link {{ request()->routeIs('bookings.index') ? 'nav-link-active' : '' }}">
                        📅 {{ __('Bookings') }}
                    </a>
                </div>

                <!-- Authentication -->
                <div class="hidden sm:flex items-center space-x-4">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 rounded-lg hover:bg-red-700 transition">
                                Log Out
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-green-600 rounded-lg hover:bg-green-700">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-700">
                                Register
                            </a>
                        @endif
                    @endauth
                </div>

                <!-- Mobile Menu -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="p-2 rounded-md text-gray-300 hover:bg-gray-700 transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto mt-12 px-6 lg:px-12 text-center">

        <!-- Pricing Section -->
        <h2 class="text-3xl font-bold mb-6">Available Rental Services</h2>
        <ul class="mt-6 space-y-4">
            <li class="bg-gray-800 p-6 rounded-lg flex justify-between text-lg font-semibold shadow-md">
                <span>🎮 PS4 Rental</span>
                <strong class="text-blue-400">Rp 30,000 per session</strong>
            </li>
            <li class="bg-gray-800 p-6 rounded-lg flex justify-between text-lg font-semibold shadow-md">
                <span>🎮 PS5 Rental</span>
                <strong class="text-blue-400">Rp 40,000 per session</strong>
            </li>
        </ul>

        <!-- Calendar Section -->
        <h2 class="text-3xl font-bold mt-10">Select Your Rental Date</h2>
        <p class="mt-2 text-gray-300">Click on a date to book your session</p>

        <div class="mt-8 bg-gray-800 p-8 rounded-lg shadow-xl">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                dateClick: function (info) {
                    @auth
                        // Redirect to create booking with the selected date
                        window.location.href = "{{ route('bookings.create') }}?date=" + info.dateStr;
                    @else
                        // Redirect to login if not authenticated
                        window.location.href = "{{ route('login') }}";
                    @endauth
                }
            });
            calendar.render();
        });
    </script>

</body>
</html>
