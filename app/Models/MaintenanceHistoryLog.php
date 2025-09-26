<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceHistoryLog extends Model
{
    use HasFactory;

    protected $table = 'maintenance_history_log';
    protected $primaryKey = 'maintenance_history_id';
    public $timestamps = false; // wala kang created_at/updated_at columns

    protected $fillable = [
        'schedule_id',
        'maintenance_date',
        'technician_id',
        'status',
    ];

    /**
     * 🔗 Relation to MaintenanceSchedule
     */
    public function schedule()
    {
        return $this->belongsTo(MaintenanceSchedule::class, 'schedule_id', 'maintenance_sched_id');
    }

    /**
     * 🔗 Relation to Technician
     */
    public function technician()
    {
        return $this->belongsTo(Technician::class, 'technician_id', 'id');
    }
}
