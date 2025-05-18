<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        Reservation::all();

    }

    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);
        return $reservation;
    }
}
