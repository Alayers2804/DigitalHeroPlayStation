<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'service', 'date', 'total_price', 'status', 'transaction_id'];

    // Automatically generate a transaction_id before saving
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($booking) {
            $booking->transaction_id = 'TRX-' . strtoupper(Str::random(10)); 
        });
    }
}
