<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Rental Booking</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="antialiased bg-gray-900 text-white">

    <!-- Navigation Bar -->
    <nav class="bg-gray-800 p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            @if (Route::has('login'))
                <div>
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-700">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-green-600 rounded-lg hover:bg-green-700">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="ml-4 px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-700">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-20 bg-gray-800 flex flex-col items-center py-6">
            <div class="mb-8">
                <img src="{{ asset('images/gambar.png') }}" alt="Logo" class="w-10 h-10">
            </div>
            <nav class="space-y-6">
                <a href="#" class="text-blue-400 hover:text-blue-600">
                    <i class="fas fa-home text-2xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-200">
                    <i class="fas fa-gamepad text-2xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-200">
                    <i class="fas fa-user text-2xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-200">
                    <i class="fas fa-cog text-2xl"></i>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 px-6 py-6">
            <div class="text-center">
                <h1 class="text-4xl font-bold">Welcome to Rental Booking</h1>

                @auth
                    <p class="mt-4 text-lg text-gray-300">
                        Hello, {{ Auth::user()->name }}! Ready to book a session?
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('bookings.create') }}"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Make a Booking
                        </a>
                        <a href="{{ route('bookings.index') }}"
                            class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 ml-4">
                            View My Bookings
                        </a>
                    </div>
                @else
                    <p class="mt-4 text-lg text-gray-300">Browse our rental services and book your session today!</p>
                @endauth
            </div>

            <!-- Featured Rental -->
            <div class="relative bg-gray-700 rounded-xl p-6 mt-8">
                <img src="{{ asset('images/ps5.jpg') }}" alt="Featured Rental" class="w-full rounded-lg">
                <div class="absolute bottom-6 left-6">
                    <h2 class="text-2xl font-bold">PS5 Rental</h2>
                    <p class="text-gray-300 mt-1">Rp 40,000 per session</p>

                    @auth
                        <a href="{{ route('bookings.create') }}"
                            class="mt-4 px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Rent Now
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="mt-4 px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Rent Now
                        </a>
                    @endauth
                </div>
            </div>


            <!-- Available Rentals -->
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
        </main>

        <!-- Social Panel -->
        <aside class="w-64 bg-gray-800 p-6">
            <h3 class="text-lg font-semibold">Social</h3>
            <div class="mt-4">
                <p class="text-gray-400 text-sm">Rocket League Update (35%)</p>
                <div class="w-full bg-gray-600 h-2 rounded-lg mt-1">
                    <div class="bg-blue-500 h-2 rounded-lg" style="width: 35%;"></div>
                </div>
            </div>

            <h4 class="text-md font-semibold mt-6">Online Friends</h4>
            <ul class="mt-2 space-y-3">
                <li class="flex items-center space-x-3">
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    <p>Bogfather - Playing Rocket League</p>
                </li>
                <li class="flex items-center space-x-3">
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    <p>MrJam - Playing Nier Automata</p>
                </li>
            </ul>
        </aside>
    </div>

</body>

</html>