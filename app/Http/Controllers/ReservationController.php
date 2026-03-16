<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResource;
use App\Mail\ConfirmReservationMail;
use App\Mail\PaymentLinkMail;
use App\Models\Car;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    public function index()
    {
        return ReservationResource::collection(
            Reservation::with('user')->with('car')->with('latestPayment')->latest()->get()
        );
    }

    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);
        return $reservation;
    }

    public static function create(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $data = $request->validate([
            "car_id"=>"required",
            "start_date"=>"required",
            "end_date"=>"required",
            "total_price"=>"required",
            "status"=>"nullable",
        ]);

        $data['user_id'] = $user->id;

        $reservation = Reservation::makeForUser($data);

        return response()->json([
            "success" => true,
            "reservation" => $reservation
        ], 201);
    }

    public static function createAsGuest(Request $request)
    {
        $data = $request->validate([
            "guest_first_name" => "required|string",
            "guest_last_name"  => "required|string",
            "guest_email"      => "required|email",
            "guest_phone"      => "required|string",
            "car_id"           => "required|integer",
            "start_date"       => "required|date",
            "end_date"         => "required|date",
            "total_price"      => "required|numeric",
            "status"           => "nullable|string",
        ]);

        $reservation = Reservation::makeForGuest($data);

        return response()->json([
            "success" => true,
            "reservation" => $reservation
        ], 201);
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

        return response()->json([
            'message' => 'Updated',
            'reservation' => $reservation
        ], 200);
    }

    public function delete(Request $request)
    {
        $reservation = Reservation::findOrFail($request->id);
        $reservation->delete();
        return response("deleted", "200")
            ->header('Content-Type', 'text/plain');
    }

    public function showCarReservations($carId)
    {
        $car = Car::findOrFail($carId);
        $reservations = $car->reservations;
        return $reservations;
    }

    public function my(Request $request)
    {
        $type = $request->query('type', 'active');
        $limit = min((int) $request->query('limit', 15), 100);

        $query = $request->user()
            ->reservations()
            ->with(['car:id,brand,model', 'latestPayment']);

        $query = match ($type) {
            'future'  => $query->future(),
            'history' => $query->history(),
            'newest'  => $query->newest(),
            default   => $query->active(),
        };

        return $query->paginate($limit);

//        $type = $request->query('type', 'active');
//
//        $limit = min((int) $request->query('limit', 15), 100);
//
//        $query = $request->user()
//            ->reservations()
//            ->with(['car:id,brand,model', 'latestPayment']);
//
//        match ($type) {
//            'future' => $query->future(),
//            'history' => $query->history(),
//            'newest'=> $query->newest(),
//            default => $query->active(),
//        };
//
//        // DASHBOARD MODE
//        if ($request->has('limit')) {
//            return $query->paginate($limit);
//        }
//
//        // FULL LIST MODE ($limit = paginate)
//        return $query->paginate($limit);
    }

    public function hasPending()
    {
        $hasPending = Reservation::where('status', 'pending')->exists();
        return response()->json([
            'hasPending' => $hasPending,
        ]);
    }

    public function sendReservationMail(Request $request)
    {
        $data = $request->validate([
            'reservation_id' => 'required',
            'email' => 'required|email',
        ]);

        $id = $data['reservation_id'];

        $reservation = Reservation::findOrFail($id);

        $car = Car::findOrFail($reservation->car_id);

        if ($reservation->user_id === null)  //guest
        {
            $mailData = [
                'user_name' => $reservation->guest_first_name,
                'user_lastname' => $reservation->guest_last_name,
                'car_name' => $car->brand." ".$car->model,
                'total_price' => $reservation->total_price,
                'start_date' => $reservation->start_date,
                'end_date' => $reservation->end_date,
            ];

        } else {
            $user = User::findOrFail($reservation->user_id);

            $mailData = [
                'user_name' => $user['name'],
                'user_lastname' => $user['name'],
                'car_name' => $car->brand." ".$car->model,
                'total_price' => $reservation->total_price,
                'start_date' => $reservation->start_date,
                'end_date' => $reservation->end_date,
            ];
        }


        Mail::to($data['email'])->send(
            new ConfirmReservationMail($mailData)
        );

        return response()->json([
            "success" => true,
            "message" => "email sent!",
        ], 200);
    }
}
