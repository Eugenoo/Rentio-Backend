<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentToken extends Model
{
    protected $fillable = [
        'reservation_id',
        'token_hash',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public static function create(array $array)
    {
        $paymentToken = new self($array);
        $paymentToken->save();
        return $paymentToken;
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

}
