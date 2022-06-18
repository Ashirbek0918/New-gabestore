<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory;

    protected   $guarded = ['id'];

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
