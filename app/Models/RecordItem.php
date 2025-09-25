<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordItem extends Model
{
    protected $primaryKey = 'record_item_id';

    protected $fillable = [
        'record_id',
        'description',
        'qty',
        'price',
        'total',
    ];

    public function record()
    {
        return $this->belongsTo(Record::class, 'record_id', 'record_id');
    }
}
