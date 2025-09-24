<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'client_name',
        'email',          // ➕ importante, ginagamit sa seeder/controller
        'service_type',
        'status',
        'rate_per_hour',
        'hours',
        'distance_km',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // 🔗 Relationship
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    // 🗑️ Cascade delete invoice kapag dinelete ang job
    protected static function booted()
    {
        static::deleting(function ($job) {
            $job->invoice()->delete();
        });
    }
}
