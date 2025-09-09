<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
    'company_name',
    'client_name',
    'client_email',
    'client_number',
    'start_date',
    'end_date',
    'equipment_type',
    'payment_type',
    'contract_details',
];

}
