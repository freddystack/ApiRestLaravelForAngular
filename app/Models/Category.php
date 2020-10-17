<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    public $products = false;
    protected $fillable =[

       'name',
       'products'
    ];

    public function products(){
        return $this->hasMany(Category::class);

    }
}
