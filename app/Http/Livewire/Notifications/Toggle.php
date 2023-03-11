<?php

namespace App\Http\Livewire\Notifications;

use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Toggle extends Component
{
    public $count = 0;

    public function mount()
    {
        $this->count = Auth::user()->unreadNotifications->count();
    }

    public function getListeners()
    {
        $userID = Auth::user()->id;

        $listenOn = [
            "updateCount" => "updateCount",
            "echo-notification:App.Models.User.{$userID},Booking\\Message" => "updateCount"
        ];

        if(Auth::user()->belongsToTeam(Team::find(1))){
            $listenOn["echo-notification:App.Models.User.{$userID},Restaurant\\Status"] = "updateCount";
        }

        if(Auth::user()->restaurants()->count()){
            $listenOn["echo-notification:App.Models.User.{$userID},Restaurant\\Booking"] = "updateCount";
        }

        return $listenOn;
    }

    public function render()
    {
        return view('livewire.notifications.toggle');
    }

    public function showNotifications()
    {
        $this->emit("showNotifications");
    }

    public function updateCount()
    {
        $this->count = Auth::user()->unreadNotifications->count();
        $this->emitSelf("tone");
    }
}
