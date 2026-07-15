<?php

namespace App\Http\Controllers;

use App\Mail\PaymentLinkMail;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Support\Str;
use App\Models\PaymentToken;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    // Tworzenie płatności dla danej rezerwacji
    public function store(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'amount' => 'required|numeric',
            'provider' => 'nullable|string',
            'status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $payment = Payment::create([
            'reservation_id' => $request->reservation_id,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'status' => $request->status,
            'provider' => $request->provider,
        ]);

        return response()->json($payment, 201);
    }

    // Aktualizacja statusu płatności (np. paid/failed)
    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $payment->update(['status' => $request->status]);

        return response()->json($payment);
    }

    // Opcjonalnie: lista płatności dla danej rezerwacji
    public function forReservation(Reservation $reservation)
    {
        return $reservation->payments()->get();
    }

    public function sendEmail(Request $request)
    {
        $data = $request->validate([
            'reservation_id' => 'required',
            'total_value' => 'required|numeric',
            'email' => 'required|email',
        ]);

        $rawToken = Str::random(64);
        $tokenHash = hash('sha256', $rawToken);

        PaymentToken::create([
            'reservation_id' => $data['reservation_id'],
            'token_hash' => $tokenHash,
            'expires_at' => now()->addHours(24),
        ]);

        $link = config('app.frontend_url') . "/payment/complete?token={$rawToken}";

        Mail::to($data['email'])->send(
            new PaymentLinkMail($link)
        );

        return response()->json([
            "success" => true,
            "message" => "Link sent successfully",
        ], 200);
    }

    public function getReservation(PaymentToken $token)
    {
        return $token;
    }

    public function verifyToken(Request $request)
    {
        $rawToken = $request->query('token');

        if (!$rawToken) {
            return response()->json(['error' => 'Brak tokenu'], 400);
        }

        $tokenHash = hash('sha256', $rawToken);

        $paymentToken = PaymentToken::where('token_hash', $tokenHash)->first();

        if (!$paymentToken) {
            return response()->json(['error' => 'Nieprawidłowy link'], 404);
        }

        if ($paymentToken->used_at) {
            return response()->json(['error' => 'Link już użyty'], 400);
        }

        if ($paymentToken->expires_at && $paymentToken->expires_at->isPast()) {
            return response()->json(['error' => 'Link wygasł'], 400);
        }

        $test = $paymentToken->reservation->user_id === null;

        return response()->json([
            'valid' => true,
            'is_guest' => $test,
            'reservation' => $paymentToken->reservation
        ]);
    }
}
