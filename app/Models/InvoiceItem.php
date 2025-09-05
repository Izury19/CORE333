<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'client_name',
        'client_email',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'description',
        'qty',
        'price',
    ];
}
