<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturesModel extends Model
{
    use HasFactory;
    protected $table = 'factures_models';
    protected $fillable =[
        'concept',
        'price',
        'units',
        'subtotal',
        'iva',
        'total'
    ];
}
