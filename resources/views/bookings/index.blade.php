@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-gray-900 text-white rounded-lg shadow-lg">
    <h2 class="text-3xl font-bold mb-6 text-center">📅 Your Bookings</h2>

    <div class="flex justify-between items-center mb-4">
        <p class="text-gray-400">Manage your bookings easily.</p>
        <a href="{{ route('bookings.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            + New Booking
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse border border-gray-700 text-gray-300">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-3 border border-gray-700">Service</th>
                    <th class="p-3 border border-gray-700">Date</th>
                    <th class="p-3 border border-gray-700">Total</th>
                    <th class="p-3 border border-gray-700">Status</th>
                    <th class="p-3 border border-gray-700">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-800 transition">
                        <td class="p-4 border border-gray-700 text-center">{{ strtoupper($booking->service) }}</td>
                        <td class="p-4 border border-gray-700 text-center">{{ $booking->date }}</td>
                        <td class="p-4 border border-gray-700 text-center font-semibold">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </td>
                        <td class="p-4 border border-gray-700 text-center">
                            <span class="px-3 py-1 rounded-lg text-sm font-bold 
                                {{ $booking->status == 'pending' ? 'bg-yellow-500 text-gray-900' : 'bg-green-500 text-white' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="p-4 border border-gray-700 text-center">
                            @if($booking->status == 'pending')
                                <form action="{{ route('bookings.pay', $booking->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                        Pay Now
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400">✅ Paid</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
