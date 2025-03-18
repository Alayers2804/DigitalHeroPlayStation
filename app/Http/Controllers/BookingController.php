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
    
        // Jika transaksi sudah dibayar, tampilkan pesan error
        if ($booking->status === 'paid') {
            return redirect()->route('bookings.index')->with('error', 'Transaction already paid. Please contact the administrator.');
        }
    
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
    
        try {
            // Generate Snap Token
            $snapToken = Snap::getSnapToken($params);
            return view('bookings.payment', compact('booking', 'snapToken'));
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
    
            return redirect()->route('bookings.index')->with('error', 'Payment request failed. Please try again or contact support.');
        }
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
            Log::error('Midtrans Callback Error: Booking Not Found', [
                'order_id' => $request->order_id,
                'database_transaction_id' => Booking::pluck('transaction_id') // Log all transaction IDs
            ]);
            return response()->json(['message' => 'Booking not found'], 404);
        }
    
        Log::info('Midtrans Callback Status Received', [
            'order_id' => $request->order_id,
            'status_code' => $request->status_code,
            'transaction_status' => $request->transaction_status
        ]);
    
        // Handle different transaction statuses
        switch ($request->transaction_status) {
            case 'settlement': // Payment completed
                $booking->status = 'paid';
                break;
    
            case 'pending': // Waiting for payment
                $booking->status = 'pending';
                break;
    
            case 'expire':
            case 'cancel':
            case 'failure': // Payment failed
                $booking->status = 'failed';
                break;
    
            default:
                Log::warning('Midtrans Callback Warning: Unhandled Status', [
                    'order_id' => $request->order_id,
                    'status' => $request->transaction_status
                ]);
                return response()->json(['message' => 'Unhandled transaction status'], 400);
        }
    
        // Force database update
        $saved = $booking->save();
        
        if (!$saved) {
            Log::error('Midtrans Callback Error: Failed to update booking status', [
                'order_id' => $request->order_id,
                'status' => $booking->status
            ]);
            return response()->json(['message' => 'Failed to update booking status'], 500);
        }
    
        Log::info('Booking Status Updated Successfully', [
            'order_id' => $request->order_id,
            'new_status' => $booking->status
        ]);
    
        return response()->json(['message' => 'Payment status updated successfully']);
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