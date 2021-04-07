<div>
    @if($bookings->count())
        @foreach($bookings as $booking)
            <x-booking.card :booking="$booking"/>
        @endforeach
    @else
        <p>No Bookings To Show...</p>
    @endif

    {{ $bookings->links() }}
</div>
