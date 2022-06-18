<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favourite extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function products(){
        return $this->hasMany(Product::class,'id','product_id');
    }
}
