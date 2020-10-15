<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable =[
       'category_id',
       'name',
       'descripcion',
       'disponible',
       'imagen',
       'stock',
       'precio-actual',
       'precio-anterior',
       'descuento'
    ];

    public function category(){
        return $this->belongsTo(Products::class);
    }
}
