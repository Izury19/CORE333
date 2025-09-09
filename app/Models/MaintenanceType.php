<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceType extends Model
{
    use HasFactory;

    // Optional: fillable properties
    protected $fillable = ['name']; // depende sa structure ng table mo
}
