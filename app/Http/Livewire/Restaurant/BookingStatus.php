<?php

namespace App\Http\Livewire\Restaurant;

use App\Events\BookingStatusUpdated;
use App\Models\Booking;
use Carbon\Carbon;
use Livewire\Component;
use Stripe\PaymentIntent;

class BookingStatus extends Component
{
    public $booking;
    public $no_show = false;
    public $no_show_fee = false;
    public $seat_table = false;
    public $reject_confirmation = false;
    public $confirm_confirmation = false;
    public $finish_table = false;
    public $fee = 0.00;

    protected $rules = [
        "booking.reject_reason" => ""
    ];

    public function mount(Booking $booking)
    {
        $this->booking = $booking;

        if(!empty($booking->payment_intent)){
            $intent = PaymentIntent::retrieve($booking->payment_intent);

            if($intent && $intent->amount_capturable){
                $this->fee = number_format($intent->amount / 100, 2);
            }
        }
    }

    public function render()
    {
        return view('livewire.restaurant.booking-status');
    }

    public function updatedNoShowFee($value)
    {
        $this->no_show = false;
    }

    public function bookingStatus($status)
    {
        if($status !== $this->booking->status){
            if(!empty($booking->payment_intent) && in_array($status, ["seated", "rejected"])){
                $intent = PaymentIntent::retrieve($this->booking->payment_intent);

                if($intent && $this->fee){
                    $intent->cancel();
                }
            }

            if($status !== "rejected"){
                $this->booking->reject_reason = null;
            }

            $this->booking->status = $status;
            $this->booking->save();
        }

        $this->no_show = false;
        $this->no_show_fee = false;
        $this->seat_table = false;
        $this->reject_confirmation = false;
        $this->confirm_confirmation = false;

        $this->emit("update-booking");
        BookingStatusUpdated::dispatch($this->booking);
    }

    public function noShow($charge = false)
    {
        if(!empty($this->booking->payment_intent)){
            $intent = PaymentIntent::retrieve($this->booking->payment_intent);

            if($intent && $this->fee){
                if($charge){
                    $intent->capture();

                    // deduct the amount from the restaurants invoice
                    $this->booking->restaurant->invoiceItems()->create([
                        "description" => "No show charged for booking #" . $this->booking->id . " (minus myTables fee)",
                        "amount" => $this->fee * -90,
                    ]);
                } else {
                    // cancel the payment intent
                    $intent->cancel();
                }
            }
        }

        $this->bookingStatus("no show");
    }

    public function finished()
    {
        $this->booking->finish_at = Carbon::now()->setTimezone("Europe/London");

        $this->booking->save();

        $this->finish_table = false;

        $this->emit("update-booking");
    }
}
