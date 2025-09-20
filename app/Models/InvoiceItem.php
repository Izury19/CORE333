<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class InvoiceItem extends Model
{
    protected $primaryKey = 'invoice_items_id';

    protected $fillable = [
        'invoice_id',
        'description',
        'qty',
        'price',
        'total',           // ✅ Required to save total!
        'client_name',
        'client_email',
        'invoice_date',
        'due_date',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }
}
