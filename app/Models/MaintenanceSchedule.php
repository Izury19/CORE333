<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'proof_image',
        'completed_at',
        'technician_name', // string lang, hindi id
    ];

    /**
     * 🔗 Relation: MaintenanceSchedule belongs to MaintenanceType
     */
    public function maintenanceType(): BelongsTo
    {
        return $this->belongsTo(MaintenanceType::class, 'maintenance_type_id', 'maintenance_types_id');
    }

    /**
     * 🔗 Relation: MaintenanceSchedule has many MaintenanceHistoryLog
     */
    public function historyLogs(): HasMany
    {
        return $this->hasMany(MaintenanceHistoryLog::class, 'schedule_id', 'maintenance_sched_id');
    }

    /**
     * ⚠️ Removed technician() kasi wala kang technician_id sa table na ito.
     * Kung gusto mo ng relation sa Technician table, dapat magdagdag ka muna ng `technician_id` column dito.
     */
}
