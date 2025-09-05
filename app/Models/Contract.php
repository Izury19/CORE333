<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'contract_title',
        'client_name',
        'start_date',
        'end_date',
        'equipment_type',
        'payment_type',
        'contract_details',
    ];
}
