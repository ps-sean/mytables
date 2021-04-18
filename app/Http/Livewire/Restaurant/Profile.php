<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Restaurant;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public $restaurant, $image;

    protected $rules = [
        "restaurant.name" => "required",
        "restaurant.email" => "required|email",
        "restaurant.phone" => "required|min:11|max:16|phone",
        "restaurant.description" => "",
    ];

    public function mount(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    public function updatedImage()
    {
        $this->validate([
            "image" => "image|max:1024",
        ]);
    }

    public function render()
    {
        return view('livewire.restaurant.profile');
    }

    public function submit()
    {
        if($this->restaurant->isDirty("email")){
            // email is being updated, it must be reverified, and the restaurant should go offline.
            $this->restaurant->email_verified_at = null;
            $this->restaurant->status = "pending";
            $this->restaurant->sendEmailVerification();
        }

        if(!empty($this->image)){
            // a new image was submitted, lets save it
            $oldImage = $this->restaurant->image_location;

            $this->restaurant->image_location = $this->image->store('restaurant/images', ["disk" => "public"]);

            if(!empty($oldImage)){
                // now that it's saved, delete the old one
                Storage::disk("public")->delete($oldImage);
            }
        }

        $this->restaurant->save();
        $this->emit("statusUpdated");
        $this->emitSelf("saved");
    }
}
