<?php

namespace App\Models;

use http\Env\Request;
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

    public static function make($request)
    {
        $reservation = new self($request);
        $reservation->save();
        return $reservation;
    }

    public  function edit($data)
    {
        $this->update($data);
        return $data;
    }
}
