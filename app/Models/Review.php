<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'car_id',
        'rating', //(1-5)
        'comment',
    ];

    public static function make($data)
    {
        $review = new self($data);
        $review->save();
        return $review;
    }

    public function edit($data)
    {
        $this->update($data);
        return $this;
    }
}
