<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use stdClass;
use function Laravel\Prompts\error;

class TimeController extends Controller
{
   public function returnTime(){
       $now = Carbon::now()->format('M:Y');
       return $now;
   }

   public function callendarInfo(Request $request)
   {

       //days in previous month
       $date = Carbon::now();
       $date->setTimezone('Europe/Warsaw');

       $monthAndDay = $date->format('M Y');
       $currentDay = $date->format('d');
       $daysOfMonth = $date->daysInMonth();
       $firstDayOfMonth = $date->startOfMonth()->format('D');
       $daysInPreviousMonth = $date->subMonth()->daysInMonth;

       return response()->json([
           'monthAndDay' => $monthAndDay,
           'currentDay' => $currentDay,
           'daysOfTheMonth' => $daysOfMonth,
           'firstDayOfMonth' => $firstDayOfMonth,
           'daysInPrevMonth' => $daysInPreviousMonth
       ]);
   }

   public function monthInfo(Request $request)
    {
        $monthOption = $request->query('option');
        $year = $request->query('year');
        $month = Carbon::createFromFormat('M' , $request->query('month'))->month;
        $date = Carbon::createFromDate($year, $month)->setTimezone('Europe/Warsaw'); //days in previous month
        if($monthOption === 'next'){
            $date->addMonth();
        }elseif ($monthOption === 'prev'){
            $date->subMonth();
        } else return 'ERROR option value must be prev or next';
        $monthAndDay = $date->format('M Y');
        $daysOfMonth = $date->daysInMonth();
        $firstDayOfMonth = $date->startOfMonth()->format('D');
        $daysInPreviousMonth = $date->subMonth()->daysInMonth;
        $date->addMonth();
        if($date->format('M Y') === Carbon::now()->setTimezone('Europe/Warsaw')->format('M Y')){
            $currentDay = Carbon::now()->setTimezone('Europe/Warsaw')->format('d');
        } else {
            $currentDay = false;
        }

        return response()->json([
            'monthAndDay' => $monthAndDay,
            'daysOfTheMonth' => $daysOfMonth,
            'currentDay' => $currentDay,
            'firstDayOfMonth' => $firstDayOfMonth,
            'daysInPrevMonth' => $daysInPreviousMonth
        ]);
    }

}
