<?php

namespace App\Models;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'minimal_system' => "json",
        "recommended_system" => "json",
        "screenshots" => "json",
        "trailers" => "json",
        "genre" => "json",
        "release" => "json",
    ];
}
