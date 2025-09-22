<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id', 'amount', 'status', 'date_paid',
    ];

    // Relation sa Invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
