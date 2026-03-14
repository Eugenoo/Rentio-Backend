<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
class Reservation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'car_id',
        'start_date',
        'end_date',
        'total_price',
        'status', //( pending, confirmed, canceled)
        'guest_first_name',
        'guest_last_name',
        'guest_email',
        'guest_phone',
    ];

    // Database relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    // Accessors

    public function getDisplayNameAttribute()
    {
        if($this->user){
            return $this->user->name;
        }
        return trim($this->guest_first_name . ' ' . $this->guest_last_name);
    }

    // Model Functions

    public static function make($request)
    {
        $reservation = new self($request);
        $reservation->save();
        return $reservation;
    }
    public static function makeForUser($request)
    {
        $reservation = new self($request);
        $reservation->save();
        return $reservation;
    }

    public static function makeForGuest($request)
    {
        $reservation = new self($request);
        $reservation->save();
        return $reservation;
    }

    public function edit($data)
    {
        $this->update($data);
        return $data;
    }

    //Scopes

    //FUTURE

    public function scopeFuture(Builder $query): Builder
    {
        return $query->where('start_date', '>', now());
    }

    // ACTIVE
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    // HISTORY
    public function scopeHistory(Builder $query): Builder
    {
        return $query->where('end_date', '<', now());
    }

    // NEWEST
    public function scopeNewest(Builder $query): Builder
    {
        return $query->latest('start_date');
    }

}
