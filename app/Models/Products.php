<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
   
    protected $fillable =[
       'category_name',
       'name',
       'descripcion',
       'disponible',
       'imagen',
       'stock',
       'precio_actual',
       'precio_anterior',
       'iva',
       'descuento'
    ];

    public function category(){
        return $this->belongsTo(Products::class);
    }
}
