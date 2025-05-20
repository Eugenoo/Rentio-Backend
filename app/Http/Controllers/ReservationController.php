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

    public static function create(Request $request)
    {
        $data = $request->validate([
            'user_id'=>"required",
            "car_id"=>"required",
            "start_date"=>"required",
            "end_date"=>"required",
            "total_price"=>"required",
            "status"=>"nullable",
        ]);

        Reservation::make($data);

        return response('Data created', 200)
            ->header('Content-Type', 'text/plain');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            "id"=>"required",
            'user_id'=>"nullable",
            "car_id"=>"nullable",
            "start_date"=>"nullable",
            "end_date"=>"nullable",
            "total_price"=>"nullable",
            "status"=>"nullable",
        ]);

        $id = $data['id'];

        $reservation = Reservation::findOrFail($id);
        $reservation->edit($data);

        Return response("Updated", "200")
            ->header('Content-Type', 'text/plain');
    }

    public function delete(Request $request)
    {
        $reservation = Reservation::findOrFail($request->id);
        $reservation->delete();
        return response("deleted", "200")
            ->header('Content-Type', 'text/plain');
    }
}
