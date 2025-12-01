<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['invoice_id','amount','method','reference','paid_at'];
    protected $dates = ['paid_at'];
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function invoice() { return $this->belongsTo(Invoice::class); }
    public function getTransactionIdAttribute()
    {
        return $this->reference;
    }
}
