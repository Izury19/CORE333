<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    // Set the correct primary key
    protected $primaryKey = 'record_id';

    // If your primary key is not auto-incrementing, set this to false
    public $incrementing = true;

    // If your primary key is not integer, set this accordingly
    protected $keyType = 'int';

    protected $fillable = [
        'invoice_id',
        'payment_uid',
        'payment_type',
        'client_name',
        'total',
        'payment_method',
        'reference_number',
        'forwarded_to_financials',
        'status'
    ];
     protected $casts = [
        'forwarded_to_financials' => 'boolean', // ADD THIS
    ];

    public function invoice()
    {
        return $this->belongsTo(BillingInvoice::class, 'invoice_id');
    }
}