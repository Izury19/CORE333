<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MaintenanceType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceSchedule extends Model
{
    protected $table = 'maintenance_schedules';

    protected $primaryKey = 'maintenance_sched_id'; // 👉 ITO ANG ID FIELD MO SA DB

    protected $fillable = [
        'equipment_name',
        'maintenance_type_id',
        'scheduled_date',
        'status',
        'technician_name',
    ];

    public function maintenanceType(): BelongsTo
    {
        return $this->belongsTo(MaintenanceType::class, 'maintenance_type_id');
    }
}

