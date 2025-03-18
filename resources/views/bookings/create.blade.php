@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-gray-900 text-white rounded-lg shadow-lg">
    <h2 class="text-3xl font-bold mb-6 text-center">🎮 Create a Booking</h2>

    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Service Selection -->
        <div>
            <label class="block text-gray-300 font-semibold mb-2">Select Service:</label>
            <select name="service" required class="w-full p-3 bg-gray-800 text-white border border-gray-700 rounded-lg">
                <option value="ps4">PS4 (Rp 30,000)</option>
                <option value="ps5">PS5 (Rp 40,000)</option>
            </select>
        </div>

        <!-- Date Selection -->
        <div>
            <label class="block text-gray-300 font-semibold mb-2">Select Date:</label>
            <input type="date" name="date" id="booking-date" required 
                class="w-full p-3 bg-gray-800 text-white border border-gray-700 rounded-lg">
        </div>

        <!-- Submit Button -->
        <button type="submit" 
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
            📅 Book Now
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get date from URL query parameter
        const urlParams = new URLSearchParams(window.location.search);
        const selectedDate = urlParams.get('date');

        // If there's a date in the URL, set it in the input field
        if (selectedDate) {
            document.getElementById('booking-date').value = selectedDate;
        }
    });
</script>
@endsection
