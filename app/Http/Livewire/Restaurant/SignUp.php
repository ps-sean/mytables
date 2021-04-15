<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\RestaurantStaff;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Stripe\Account;

class SignUp extends Component
{
    public $name = "";
    public $address = "";
    public $phone = "";
    public $email = "";
    public $sessionToken;
    public $addressResults = [];
    public $addressJSON = "";

    protected $rules = [
        'name' => 'required|min:6',
        'email' => 'required|email|unique:restaurants',
        'phone' => ['required', 'unique:restaurants', 'min:11', 'max:16', 'regex:^(((\+44\s?\d{4}|\(?0\d{4}\)?)\s?\d{3}\s?\d{3})|((\+44\s?\d{3}|\(?0\d{3}\)?)\s?\d{3}\s?\d{4})|((\+44\s?\d{2}|\(?0\d{2}\)?)\s?\d{4}\s?\d{4}))(\s?\#(\d{4}|\d{3}))?$^']
    ];

    public function mount()
    {
        $this->sessionToken = Str::uuid()->toString();
    }

    public function render()
    {
        return view('livewire.restaurant.sign-up');
    }

    public function updated($property)
    {
        if($property === "address"){
            // check with the places API for this address
            $client  = new Client();

            $parameters = [
                "query" => [
                    "input" => $this->address,
                    "key" => config('services.google.key'),
                    "sessionToken" => $this->sessionToken,
                ]
            ];

            $res = $client->get('https://maps.googleapis.com/maps/api/place/autocomplete/json', $parameters);

            $this->addressResults = json_decode($res->getBody())->predictions;
        } else {
            $this->validateOnly($property);
        }
    }

    public function resetSession()
    {
        $this->address = "";
        $this->addressJSON = "";
        $this->addressResults = [];
        $this->sessionToken = Str::uuid()->toString();
    }

    public function selectPlace($placeID)
    {
        // check with the places API for this address
        $client  = new Client();

        $parameters = [
            "query" => [
                "key" => config('services.google.key'),
                "place_id" => $placeID,
            ]
        ];

        $res = $client->get('https://maps.googleapis.com/maps/api/place/details/json', $parameters);

        $result = json_decode($res->getBody())->result;
        $this->address = $result->formatted_address;
        $this->addressJSON = json_encode($result);
        $this->addressResults = [];

        $this->resetErrorBag();
        $this->validateAddress();
    }

    public function validateAddress()
    {
        if(empty($this->addressJSON)){
            $this->addError("address", "A valid address is required");
            return false;
        }

        return true;
    }

    public function submit()
    {
        $this->validate();

        if(!$this->validateAddress()){
            return;
        }

        $address = json_decode($this->addressJSON);

        $account = Account::create([
            'type' => 'express'
        ]);

        $restaurant = Auth::user()->restaurants()->create([
            "name" => $this->name,
            "email" => $this->email,
            "phone" => $this->phone,
            "address_line_1" => $address->name,
            "vicinity" => $address->vicinity ?? $this->getAddressPart($address, "postal_town"),
            "country" => $this->getAddressPart($address, "country"),
            "postal_code" => $this->getAddressPart($address, "postal_code"),
            "lat" => $address->geometry->location->lat,
            "lng" => $address->geometry->location->lng,
            "stripe_acct_id" => $account->id
        ]);

        if($restaurant){
            $restaurant_staff = RestaurantStaff::create([
                "restaurant_id" => $restaurant->id,
                "user_id" => Auth::user()->id,
            ]);

            if($restaurant_staff){
                return $restaurant->linkAccount();
            } else {
                $restaurant->delete();
                $this->addError("submit_fail", "Something went wrong, and we couldn't link your account to your restaurant");
            }
        } else {
            $this->addError("submit_fail", "Something went wrong, and we couldn't create your restaurant's profile");
        }
    }

    public function getAddressPart($address, $part)
    {
        foreach($address->address_components as $component){
            if(in_array($part, $component->types)){
                return $component->long_name;
            }
        }

        return false;
    }
}
