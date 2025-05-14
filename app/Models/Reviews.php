<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'car_id',
        'rating', //(1-5)
        'comment',
    ];
}
