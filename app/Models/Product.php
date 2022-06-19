<?php

namespace App\Models;

use App\Models\Genre;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'minimal_system' => "json",
        "recommended_system" => "json",
        "screenshots" => "json",
        "trailers" => "json",
        "release" => "json",
    ];

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
