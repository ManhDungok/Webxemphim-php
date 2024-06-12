<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTopUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'amount',
        'status',
        'payment_info'
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
    
}