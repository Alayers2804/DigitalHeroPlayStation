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
        Log::info('Midtrans Callback Received:', $request->all());

        // Validate required fields exist
        if (!$request->has(['order_id', 'status_code', 'gross_amount', 'signature_key', 'transaction_status'])) {
            Log::error('Midtrans Callback Error: Missing required fields', $request->all());
            return response()->json(['message' => 'Invalid request'], 400);
        }

        // Verify Midtrans signature
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $expectedSignature = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($expectedSignature !== $request->signature_key) {
            Log::error('Midtrans Callback Error: Invalid Signature', [
                'expected' => $expectedSignature,
                'received' => $request->signature_key
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Find the corresponding booking
        $booking = Booking::where('transaction_id', $request->order_id)->first();

        if (!$booking) {
            Log::error('Midtrans Callback Error: Booking Not Found', ['order_id' => $request->order_id]);
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Update booking status based on transaction status
        switch ($request->transaction_status) {
            case 'settlement': // Payment completed
                $booking->update(['status' => 'paid']);
                Log::info('Booking Payment Settled', ['order_id' => $request->order_id]);
                break;

            case 'pending': // Waiting for payment
                $booking->update(['status' => 'pending']);
                Log::info('Booking Payment Pending', ['order_id' => $request->order_id]);
                break;

            case 'expire':
            case 'cancel':
            case 'failure': // Payment failed
                $booking->update(['status' => 'failed']);
                Log::info('Booking Payment Failed', ['order_id' => $request->order_id]);
                break;

            default:
                Log::warning('Midtrans Callback Warning: Unhandled Status', ['order_id' => $request->order_id, 'status' => $request->transaction_status]);
                return response()->json(['message' => 'Unhandled transaction status'], 400);
        }

        return response()->json(['message' => 'Payment status updated']);
    }

    public function dashboard()
    {
        $user = auth()->user()->load('bookings'); // Load bookings relationship
        $bookings = $user->bookings;

        return view('dashboard', compact('bookings'));
    }

    private function calculateTotal($service, $date)
    {
        $basePrice = ($service == 'ps4') ? 30000 : 40000;
        $surcharge = (date('N', strtotime($date)) >= 6) ? 50000 : 0;
        return $basePrice + $surcharge;
    }
}