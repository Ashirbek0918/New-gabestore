<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Developer extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    // function brings products of a Developer,
    public function products(){
        return $this->hasMany(Product::class);
    }
}
