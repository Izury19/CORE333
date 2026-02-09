<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $primaryKey = 'contract_id';
    
    protected $fillable = [
        'contract_number',
        'contract_type',
        'company_name',
        'client_name',
        'client_email',
        'client_number',
        'start_date',
        'end_date',
        'contract_details',
        'total_amount',        // ✅ ADD THIS
        'payment_type',        // ✅ ADD THIS  
        'equipment_type',      // ✅ ADD THIS
        'status',
        'submitted_by',
        'legal_reference'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_amount' => 'decimal:2', // ✅ CAST AS DECIMAL
    ];
}