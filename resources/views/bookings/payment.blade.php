@extends('layouts.app')

@section('content')
<h2>Payment for Booking #{{ $booking->id }}</h2>
<p>Amount: Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
<button id="pay-button">Pay Now</button>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', function() {
        window.snap.pay("{{ $snapToken }}", {
            onSuccess: function(result) {
                alert('Payment successful!');
                window.location.href = "{{ route('bookings.index') }}";
            },
            onPending: function(result) {
                alert('Waiting for payment!');
            },
            onError: function(result) {
                alert('Payment failed!');
            }
        });
    });
</script>
@endsection
