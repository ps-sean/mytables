<?php

namespace App\Http\Livewire\Notifications;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Panel extends Component
{
    use WithPagination;

    public $count;
    public $showNotifications = false;

    protected $listeners = ['showNotifications' => 'openNotifications', 'updateCount' => 'updateCount'];

    public function render()
    {
        $this->count = Auth::user()->unreadNotifications->count();

        return view('livewire.notifications.panel', [
            'notifications' => Auth::user()->notifications()->paginate(15)
        ]);
    }

    public function openNotifications()
    {
        $this->showNotifications = true;
    }

    public function followNotification($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if(!$notification){
            return false;
        }

        $notification->markAsRead();

        return redirect()->to($notification->data['link']);
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        $this->emit("updateCount");
    }

    public function updateCount()
    {
        $this->count = Auth::user()->unreadNotifications->count();
    }
}
