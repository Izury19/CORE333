<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceSchedule extends Model
{
    protected $table = 'maintenance_schedules';

    protected $primaryKey = 'maintenance_sched_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'equipment_name',
        'maintenance_type_id', // foreign key papunta sa maintenance_types
        'scheduled_date',
        'status',
        'technician_name', // string lang, hindi id
    ];

    /**
     * Relation: MaintenanceSchedule belongs to MaintenanceType
     */
    public function maintenanceType(): BelongsTo
    {
        return $this->belongsTo(MaintenanceType::class, 'maintenance_type_id', 'maintenance_types_id');
    }
}
