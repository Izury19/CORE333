<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // 👇 Ito ang fix
    protected $primaryKey = 'payment_id';
    public $incrementing = true; // kung auto-increment si payment_id, keep this true
    protected $keyType = 'int';  // kung integer ang payment_id

    protected $fillable = [
        'invoice_id',
        'amount',
        'proof',
        'status',
        'date_paid',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }
}
