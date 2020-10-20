<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesModel extends Model
{
    use HasFactory;
    protected $table = 'sales_models';
    protected $fillable =[
        'profist',
        'losses',
        'best_seller',
        'quantity_best_seller',
        'less_sold',
        'quantity_less_sold'
    ];
}
