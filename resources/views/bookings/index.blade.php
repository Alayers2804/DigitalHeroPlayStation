@extends('layouts.app')

@section('content')
    <h2>Your Bookings</h2>
    <a href="{{ route('bookings.create') }}">New Booking</a>
    <table>
        <tr>
            <th>Service</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        @foreach($bookings as $booking)
            <tr>
                <td>{{ $booking->service }}</td>
                <td>{{ $booking->date }}</td>
                <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                <td>{{ $booking->status }}</td>
                <td>
                    @if($booking->status == 'pending')
                        <form action="{{ route('bookings.pay', $booking->id) }}" method="POST">
                            @csrf
                            <button type="submit">Pay Now</button>
                        </form>
                    @else
                        Paid
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
@endsection