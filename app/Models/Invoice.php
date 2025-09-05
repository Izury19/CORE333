<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InvoiceItem; // import ng InvoiceItem

class Invoice extends Model
{
    protected $fillable = [
        'client_name',
        'client_email',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax',
        'total',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
