<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MaintenanceType;

class MaintenanceSchedule extends Model
{
    protected $table = 'maintenance_schedules'; // check kung tama ang table name mo

    protected $fillable = [
        'equipment_name',
        'maintenance_type_id',
        'scheduled_date',
        'status',
        'technician_name',
    ];

    // Add this relationship method here
    public function maintenanceType()
    {
        return $this->belongsTo(MaintenanceType::class, 'maintenance_type_id');
    }
}
