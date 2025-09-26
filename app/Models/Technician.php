<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    use HasFactory;

    protected $table = 'technicians';           // table name
    protected $primaryKey = 'technicians_id';   // custom primary key
    protected $fillable = ['name', 'email', 'image'];

}
