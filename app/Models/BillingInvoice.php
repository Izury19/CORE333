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
 // Sa BillingInvoice model (app/Models/BillingInvoice.php)
public static function generateUid($equipmentType)
{
    $prefix = $equipmentType === 'crane' ? 'CRN' : 'TRK';
    $year = now()->format('Y');
    
    // Get the highest existing ID for this prefix and year
    $lastInvoice = self::where('invoice_uid', 'like', "{$prefix}-{$year}-%")
        ->orderBy('invoice_uid', 'desc')
        ->first();
    
    if ($lastInvoice) {
        // Extract the number from the UID (e.g., TRK-2026-0002 → 2)
        $parts = explode('-', $lastInvoice->invoice_uid);
        $lastNumber = (int) end($parts);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }
    
    return "{$prefix}-{$year}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
}

public function getInvoiceUidAttribute()
{
    return 'INV-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
}
}