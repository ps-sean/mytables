<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $device = "myTables Widget";

        if(session()->has("restaurant")){
            $restaurant = Restaurant::find(session("restaurant"));
            $device .= " - " . $restaurant->name;
        } else {
            $device .= " - UNKNOWN";
        }

        $response = Http::withHeaders([
            "Accept" => "application/json"
        ])->post(config("app.url") . '/api/auth/login', [
            "email" => $request->email,
            "password" => $request->password,
            "device_name" => $device,
        ]);

        $result = $response->json();

        if($response->status() == 200){
            dd(session()->all());
        } else {
            return redirect()->back();
        }
    }

    public function logout()
    {
        $response = Http::withHeaders([
            "Accept" => "application/json"
        ])->post(config("app.url") . '/api/auth/logout');

        dd(session()->all());

        return redirect()->back();
    }

    public function user()
    {
        $response = Http::withHeaders([
            "Accept" => "application/json"
        ])->get(config("app.url") . '/api/user');

        dd($response->json());
    }
}
