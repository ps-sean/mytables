<?php

namespace App\Http\Controllers;

use App\Jobs\Restaurant\InvoiceDay;
use App\Jobs\UnreadMessageEmailer;
use App\Models\Restaurant;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $restaurants = collect([]);

        if(!empty($request->search)){
            $restaurants = Restaurant::where([
                ["name", "LIKE", "%" . $_GET['search'] . "%"],
                ["status", "live"],
            ])->get();

            $restaurants = $restaurants->merge(SearchController::byLocation($request));

            $restaurants = $this->paginate($restaurants->unique(), 15);
        }

        return view("welcome", compact([
            "restaurants"
        ]));
    }

    public static function byLocation(Request $request)
    {
        $restaurants = collect([]);

        try{
            // check with the geocode API for this address
            $client  = new Client();

            $parameters = [
                "timeout" => 5,
                "query" => [
                    "address" => $request->search,
                    "key" => config('services.google.key'),
                    "region" => "UK"
                ]
            ];

            $res = $client->get('https://maps.googleapis.com/maps/api/geocode/json', $parameters);

            $results = json_decode($res->getBody())->results;

            if(count($results)){
                foreach($results as $result){
                    $restaurants = $restaurants->merge(SearchController::byRadius($result->geometry->location->lat, $result->geometry->location->lng, $request->distance ?? 5));
                }
            }
        } catch (\Exception $e) {
            // do nothing, just skip right past, like nothing happened...
        }

        return $restaurants;
    }

    public static function byRadius($lat, $lng, $distance = 5)
    {
        return Restaurant::where("status", "live")
            ->whereRaw(SearchController::radiusQuery($lat, $lng) . "<" . $distance)
            ->selectRaw("*, " . SearchController::radiusQuery($lat, $lng) . " as distance")
            ->get();
    }

    public static function radiusQuery($lat, $lng)
    {
        return "69 *
        DEGREES(ACOS(LEAST(1.0, COS(RADIANS(restaurants.lat))
         * COS(RADIANS(" . $lat . "))
         * COS(RADIANS(restaurants.lng - " . $lng . "))
         + SIN(RADIANS(restaurants.lat))
         * SIN(RADIANS(" . $lat . ")))))";
    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
