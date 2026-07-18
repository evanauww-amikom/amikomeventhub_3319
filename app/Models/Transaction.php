<?php
// app/Models/Transaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    
protected $fillable = [
    'event_id',
    'user_id',
    'order_id',
    'customer_name',
    'customer_email',
    'customer_phone',
    'total_price',
    'status',
    'check_in_status', // Tambahan kolom sinkronisasi
    'checked_in_at',   // Tambahan kolom sinkronisasi
    'snap_token',
];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}