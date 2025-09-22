<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceType extends Model
{
    use HasFactory;

    protected $table = 'maintenance_types';
    protected $primaryKey = 'maintenance_types_id';

    protected $fillable = ['name', 'frequency'];
}

