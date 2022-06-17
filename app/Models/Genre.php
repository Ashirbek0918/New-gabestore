<?php

namespace App\Models;

use App\Models\Product;
use App\Models\GenreProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Genre extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name'
    ];
    // This function brings all genres from GenreProduct model to the Genre model.
    public function genre_products(){
        return $this->hasMany(GenreProduct::class);
    }

}
