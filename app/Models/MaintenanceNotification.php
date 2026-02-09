<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceNotification extends Model
{
    protected $fillable = [
        'equipment_name',
        'equipment_id', 
        'scheduled_date',
        'notification_type',
        'message',
        'is_read'
    ];
}