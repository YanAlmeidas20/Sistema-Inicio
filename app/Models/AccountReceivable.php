<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountReceivable extends Model
{
    protected $fillable = [
        'description', 
        'amount', 
        'due_date', 
        'is_paid', 
        'partner_id', 
        'receipt_file',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'due_date' => 'date',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function getReceiptFileUrlAttribute()
    {
        return $this->receipt_file 
            ? asset('storage/' . $this->receipt_file) 
            : null;
    }
}
