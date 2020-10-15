<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable =[
       'products_id',
       'name'
    ];

    public function products(){
        return $this->hasMany(Category::class);

    }
}
