<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $primaryKey = 'record_id';

    protected $fillable = [
        'invoice_id',
        'client_name',
        'client_email',
        'client_address',
        'total',
        'payment_method',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(RecordItem::class, 'record_id', 'record_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }
}
