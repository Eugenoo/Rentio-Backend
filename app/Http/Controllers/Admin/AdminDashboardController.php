<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Car;
use App\Models\Payment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        return response()->json([
            'stats' => [
                'users' => User::count(),
                'cars' => Car::count(),
                'active_reservations' => Reservation::active()->count(),
                'monthly_revenue' => Payment::whereMonth('created_at', now()->month)
                    ->where('status', 'paid')
                    ->sum('amount')
            ],
            'latest_users' => User::latest()->take(5)->get(),
            'latest_reservations' => Reservation::with('user', 'car')
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }

    public function revenueChart(Request $request)
    {
        $start = Carbon::parse($request->query('start', now()->startOfMonth()));
        $end   = Carbon::parse($request->query('end', now()->endOfMonth()));

        $reservations = Reservation::with('payments')
            ->where('status', 'confirmed')
            ->get();

        $confirmed = [];
        $paid = [];

        foreach ($reservations as $reservation) {
            $period = CarbonPeriod::create(
                $reservation->start_date,
                $reservation->end_date
            );

            foreach ($period as $date) {

                if (!$date->between($start, $end)) continue;

                $key = $date->format('Y-m-d');

                $confirmed[$key] = ($confirmed[$key] ?? 0) + $reservation->price_per_day;

                dd($reservation);

                if (
                    $reservation->latestPayment &&
                    $reservation->latestPayment->status === 'paid'
                ) {
                    $paid[$key] = ($paid[$key] ?? 0) + $reservation->price_per_day;
                }
            }
        }

        $labels = collect(array_keys($confirmed))
            ->merge(array_keys($paid))
            ->unique()
            ->sort()
            ->values();

        return response()->json([
            'labels' => $labels,
            'confirmed' => $labels->map(fn($d) => $confirmed[$d] ?? 0),
            'paid' => $labels->map(fn($d) => $paid[$d] ?? 0),
        ]);
    }

    public function cashflowChart(Request $request)
    {
        $start = $request->query('start', now()->startOfMonth());
        $end   = $request->query('end', now()->endOfMonth());

        $payments = Payment::where('status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'labels' => $payments->pluck('date'),
            'totals' => $payments->pluck('total'),
        ]);
    }
}
