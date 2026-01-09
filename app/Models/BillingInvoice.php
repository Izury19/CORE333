<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingInvoice extends Model
{
    use HasFactory;

    protected $table = 'billing_invoices';

    protected $fillable = [
        'invoice_uid',
        'contract_id',
        'client_name',
        'equipment_type',
        'equipment_id',
        'hours_used',
        'hourly_rate',
        'total_amount',
        'billing_period_start',
        'billing_period_end',
        'status'
    ];

    // 🔑 UNIQUE INVOICE ID GENERATOR
    public static function generateUid($equipmentType)
    {
        $year = date('Y');
        $prefix = $equipmentType === 'crane' ? 'CR' : 'TR';

        $last = self::where('invoice_uid', 'like', "INV-{$prefix}-{$year}-%")
                    ->orderBy('id', 'desc')
                    ->first();

        $number = $last ? (int)substr($last->invoice_uid, -3) + 1 : 1;
        return "INV-{$prefix}-{$year}-" . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}