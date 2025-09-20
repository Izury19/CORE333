<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InvoiceItem;

class Invoice extends Model
{
    protected $primaryKey = 'invoice_id';
    // Status constants
    const STATUS_UNPAID = 'unpaid';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CANCELLED = 'cancelled';

    // Fillable fields (make sure 'status' is included)
    protected $fillable = [
        'client_name',
        'client_email',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'status', // <-- important
    ];

    protected $casts = [
        'invoice_date' => 'datetime',
        'due_date' => 'datetime',
    ];

    // Relationship
    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'invoice_id');
    }

    // Optional helper: returns all possible statuses
    public static function getStatuses()
    {
        return [
            self::STATUS_UNPAID,
            self::STATUS_PAID,
            self::STATUS_OVERDUE,
            self::STATUS_CANCELLED,
        ];
    }
}
