<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'permit_type',
        'permit_number',
        'issuing_agency',
        'issue_date',
        'expiry_date',
        'document_path',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Get the truck that owns the permit
     */
    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    /**
     * Scope: Active permits (not expired)
     */
    public function scopeActive($query)
    {
        return $query->where('expiry_date', '>=', now());
    }

    /**
     * Scope: Expired permits
     */
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    /**
     * Scope: Expiring soon (within 30 days)
     */
    public function scopeExpiringSoon($query)
    {
        return $query->whereBetween('expiry_date', [now(), now()->addDays(30)]);
    }

    /**
     * Get days remaining until expiry
     */
    public function getDaysRemainingAttribute()
    {
        return $this->expiry_date->diffInDays(now(), false);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        if ($this->expiry_date < now()) {
            return 'Expired';
        }
        if ($this->expiry_date <= now()->addDays(30)) {
            return 'Expiring Soon';
        }
        return 'Active';
    }

    /**
     * Check if permit has document
     */
    public function getHasDocumentAttribute()
    {
        return !empty($this->document_path);
    }

    /**
     * Get document URL
     */
    public function getDocumentUrlAttribute()
    {
        return $this->document_path ? asset('storage/' . $this->document_path) : null;
    }
}