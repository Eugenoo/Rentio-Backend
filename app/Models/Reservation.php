<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'car_id',
        'start_date',
        'end_date',
        'total_price',
        'status', //( pending, confirmed, canceled)
    ];
}
