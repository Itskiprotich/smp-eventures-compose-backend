<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class Statistics extends Controller
{
    //

    public function load_milk_expressions(Request $request, $ip)
    {
        $data = [];
        $times = 8;
        for ($i = 0; $i < $times; $i++) {
            $hour = 3 * $i;
            $date = Carbon::now();
            $hour = $date->subHours($hour);
            $time = $hour->format('h:i A');
            $data[] = ([
                "cool" => $hour,
                "time" => $time,
                "amount" => rand(10, 150)
            ]);
        }

        $fine = collect($data)->sortBy('cool')->values();
        return response()->json([
            "totalAmount" => "250 ml",
            "varianceAmount" => "+15 ml",
            "data" => $fine
        ], 200);
    }

    public function load_feeds_distribution(Request $request, $ip)
    {
        $data = [];
        $times = 8;
        for ($i = 0; $i <= $times; $i++) {
            $hour = 3 * $i;
            $date = Carbon::now();
            $hour = $date->subHours($hour);
            $time = $hour->format('h:i A');
            $data[] = ([
                "cool" => $hour,
                "time" => $time,
                "ivVolume" => rand(10, 150),
                "ebmVolume" => rand(10, 150),
                "dhmVolume" => rand(10, 150)
            ]);
        }

        $fine = collect($data)->sortBy('cool')->values();
        return response()->json([
            "totalFeed" => "250 ml",
            "varianceAmount" => "+15 ml",
            "data" => $fine
        ], 200);
    }

    public function general_statistics(Request $request)
    {
        $expressingTime = [];
        $mortality = [];
        $current = Carbon::now();
        $month = $current->format('m');

        for ($i = 0; $i < $month; $i++) {
            $full = Carbon::now()->subMonths($i);
            $mm = $full->format('M');
            $expressingTime[] = ([
                "month" => $mm,
                "underFive" => rand(10, 150),
                "underSeven" => rand(10, 150),
                "aboveSeven" => rand(10, 150)
            ]);
            $mortality[] = ([
                "month" => $mm,
                "value" => rand(13, 50)
            ]);
        }

        $firstFeeding = array("withinOne" => rand(10, 150), "afterOne" => rand(10, 20), "afterTwo" => rand(40, 90), "afterThree" => rand(30, 150));
        $percentageFeeds = array("dhm" => 35, "breastFeeding" => 37, "oral" => 43, "ebm" => 40, "formula" => 5);


        $mort = array_reverse($mortality);
        $times = array_reverse($expressingTime);
        $mortalityRate = array("rate" => "3%", "data" => $mort);

        return response()->json([
            "totalBabies" => 10,
            "preterm" => 4,
            "term" => 6,
            "averageDays" => 3,
            "firstFeeding" => $firstFeeding,
            "percentageFeeds" => $percentageFeeds,
            "mortalityRate" => $mortalityRate,
            "expressingTime" => $times,
            "mortalityRate" => $mortalityRate
        ], 200);
    }

    public function general_dhm(Request $request)
    {
        $available = [];
        $daysCount = 7;

        for ($i = 0; $i < $daysCount; $i++) {
            $current = Carbon::now();
            $day = $current->subDays($i)->format('l');
            $preterm = rand(1, 50);
            $term = rand(1, 50);
            $total = $preterm + $term;
            $available[] = ([
                "day" => $day,
                "preterm" => $preterm,
                "term" => $term,
                "total" => $total
            ]);
        }

        $data = array_reverse($available);

        return response()->json([
            "dhmInfants" => 10,
            "dhmVolume" => "40 ml",
            "dhmAverage" => "55 ml",
            "fullyReceiving" => 3,
            "dhmLength" => "3 Days",
            "data" => $data
        ], 200);
    }

    public function general_weights(Request $request, $ip)
    {

        $available = [];
        $daysCount = 10;
        $projected = 2500;

        for ($i = 0; $i <= $daysCount; $i++) {
            $projected += 300;
            $value = rand(2000, 5000);
            $available[] = ([
                "lifeDay" => $i,
                "actual" => $value,
                "projected" => $projected
            ]);
        }

        $data = array_reverse($available);

        return response()->json([
            "currentWeight" => $ip, // "3400 gm",
            "data" => $available
        ], 200);
    }
}