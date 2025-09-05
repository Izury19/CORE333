<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    protected $table = 'maintenance_schedules'; // check kung tama ang table name mo

    protected $fillable = [
        'equipment_name',
        'type',
        'scheduled_date',
        'status',
        'technician_name',
    ];
}
