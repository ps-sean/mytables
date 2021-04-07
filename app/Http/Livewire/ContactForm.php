<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactForm as MailContactForm;
use Livewire\Component;

class ContactForm extends Component
{
    public $name, $email, $subject, $text, $extras, $to;
    public $subjectDisabled = false;

    protected $rules = [
        "name" => 'required',
        "email" => 'required|email',
        "subject" => 'required',
        "text" => 'required',
    ];

    public function mount($subject = null, $to = "info@mytables.co.uk", $extras = [])
    {
        if(!empty($subject)){
            $this->subject = $subject;
            $this->subjectDisabled = true;
        }

        if(Auth::check()){
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
        }

        $this->to = $to;
        $this->extras = $extras;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.contact-form');
    }

    public function submit()
    {
        $this->validate();

        $text = $this->text;

        if(!empty($this->extras)){
            $text = implode("\r\n", $this->extras) . "\r\n \r\n" . $text;
        }

        Mail::to($this->to)->send(new MailContactForm($this->name, $this->email, $this->subject, $text));

        if(Mail::failures()){
            $this->emitSelf("failed");
        } else {
            $this->emitSelf("sent");
            $this->reset(['name', 'email', 'text']);
        }
    }
}
