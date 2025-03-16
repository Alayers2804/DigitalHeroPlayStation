@extends('layouts.app')

@section('content')
<h2>Create Booking</h2>
<form action="{{ route('bookings.store') }}" method="POST">
    @csrf
    <label>Service:</label>
    <select name="service" required>  <!-- Add name="service" -->
        <option value="ps4">PS4 (Rp 30,000)</option>
        <option value="ps5">PS5 (Rp 40,000)</option>
    </select>

    <label>Date:</label>
    <input type="date" name="date" required>

    <button type="submit">Book Now</button>
</form>
@endsection
