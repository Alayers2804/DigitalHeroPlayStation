@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-10 text-center">
        <h2 class="text-4xl font-bold">Your Booking Status</h2>
        <p class="mt-2 text-gray-300">Here is the current status of your bookings</p>

        <!-- Tampilkan daftar pemesanan pengguna -->
        <div class="mt-6 bg-gray-800 p-6 rounded-lg shadow-lg">
            @if (!empty($bookings) && is_iterable($bookings))
                <ul class="space-y-3">
                    @foreach ($bookings as $booking)
                        <li class="bg-gray-700 p-4 rounded-lg flex justify-between">
                            <span>{{ $booking->service }} - {{ $booking->date }}</span>
                            <strong>{{ $booking->status }}</strong>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-300 mt-4">You have no bookings yet.</p>
            @endif
        </div>
    </div>
@endsection