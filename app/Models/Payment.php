<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'payments_id'; // ✅ ito yung nasa DB mo
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'invoice_id',
        'amount',
        'proof',
        'status',
        'payment_date', // ✅ dapat same sa DB column
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }
}
