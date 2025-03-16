<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure users are logged in
    }

    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())->get();
        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        return view('bookings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'service' => 'required',
            'date' => 'required|date',
        ]);

        $total = $this->calculateTotal($request->service, $request->date);

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'service' => $request->service,
            'date' => $request->date,
            'total_price' => $total,
            'status' => 'pending',
            'transaction_id' => uniqid('trx_') // Unique transaction ID
        ]);


        return redirect()->route('bookings.index')->with('success', 'Booking created successfully!');
    }

    public function pay(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
    
        // Ensure transaction_id exists
        if (!$booking->transaction_id) {
            $booking->transaction_id = 'TRX-' . strtoupper(Str::random(10));
            $booking->save();
        }
    
        // Configure Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    
        // Payment details
        $params = [
            'transaction_details' => [
                'order_id' => $booking->transaction_id,
                'gross_amount' => $booking->total_price,
            ],
            'customer_details' => [
                'user_id' => $booking->user_id,
                'email' => Auth::user()->email,
            ]
        ];
    
        // Generate Snap Token
        $snapToken = Snap::getSnapToken($params);
    
        return view('bookings.payment', compact('booking', 'snapToken'));
    }
    public function midtransCallback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed != $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Find booking by transaction ID
        $booking = Booking::where('transaction_id', $request->order_id)->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($request->transaction_status == 'settlement') {
            $booking->update(['status' => 'paid']);
        } elseif ($request->transaction_status == 'pending') {
            $booking->update(['status' => 'pending']);
        } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'expire' || $request->transaction_status == 'failure') {
            $booking->update(['status' => 'failed']);
        }

        return response()->json(['message' => 'Payment status updated']);
    }



    private function calculateTotal($service, $date)
    {
        $basePrice = ($service == 'ps4') ? 30000 : 40000;
        $surcharge = (date('N', strtotime($date)) >= 6) ? 50000 : 0;
        return $basePrice + $surcharge;
    }
}