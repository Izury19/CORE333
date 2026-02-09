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

    // ✅ UPDATED: Added ALL fields including AI fields to $fillable array
    protected $fillable = [
        'equipment_name',
        'maintenance_type_id',
        'scheduled_date',
        'priority',
        'status',
        'is_recurring',
        'recurrence_type',
        'recurrence_frequency',
        'recurrence_end_date',
        'proof_image',
        'completed_at',
        
        // ✅ AI FIELDS (NEW)
        'ai_risk_score',
        'ai_predicted_failure_date',
        'ai_recommendations'
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
}