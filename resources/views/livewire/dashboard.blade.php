<div class="space-y-10">
    <div>
        <x-select wire:model.live="rid">
            @foreach($restaurants as $r)
                <option value="{{ $r->id }}">{{ $r }}</option>
            @endforeach
        </x-select>
    </div>

    <div>
        <h2 class="font-bold text-3xl">Billing</h2>
        <div class="grid grid-flow-row lg:grid-flow-col lg:auto-cols-fr gap-6 p-6 border border-gray-100 shadow rounded">
            <div class="flex flex-col items-center justify-center px-6 text-center">
                <p class="text-6xl">&pound;{{ $restaurant->monthly_payment }}</p>
                <p>Your typical monthly payment</p>
                <p class="text-gray-600 text-sm mt-3">
                    This is calculated based on the number of bookable tables in your restaurant multiplied by your
                    monthly rate of &pound;{{ $restaurant->rate }} per table.
                </p>
            </div>
            <div class="flex flex-col items-center justify-center px-6 text-center lg:border-l lg:border-r">
                <p class="text-5xl">{{ $restaurant->next_billing_date->toFormattedDateString() }}</p>
                <p>Next payment date</p>
                <p class="text-gray-600 text-sm mt-3">
                    The date of your next payment is driven by the date you set in your billing settings.
                </p>
            </div>
            <div class="flex flex-col items-center justify-center px-6 text-center">
                <p class="text-6xl">&pound;{{ $restaurant->next_payment_amount }}</p>
                <p>Next payment amount (expected)</p>
                <p class="text-gray-600 text-sm mt-3">
                    This is calculated based on the costs you have already accrued, plus the number of bookable tables
                    you have at this moment in time multiplied by the number of days left until your next payment date.
                </p>
            </div>
            @if($balance = $restaurant->asStripeCustomer()->balance)
                <div class="flex flex-col items-center justify-center px-6 text-center lg:border-l">
                    <p class="text-6xl">{{ $balance < 0 ? "-" : "" }}&pound;{{ number_format(abs($balance)/100, 2) }}</p>
                    <p>Account Balance</p>
                    <p class="text-gray-600 text-sm mt-3">
                        @if($balance < 0)
                            A credit will be applied to your next invoice.
                        @else
                            A debit will be applied to your next invoice.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    <div>
        <h2 class="font-bold text-3xl">Bookings</h2>
        <div id="bookings_chart" class="w-full h-75"></div>
        <div id="covers_chart" class="w-full h-75"></div>
    </div>

    <div>
        <h2 class="font-bold text-3xl">Reviews</h2>
        <div id="reviews_chart" class="w-full h-75"></div>
    </div>

    @push("scripts")
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                google.charts.load('current', {'packages':['corechart']})
                google.charts.setOnLoadCallback(drawChart)

                function drawChart() {
                    let data = google.visualization.arrayToDataTable(@this.bookings)

                    let options = {
                        title: 'Booking Comparison vs. Next/Last Week',
                        curveType: 'function',
                        legend: { position: 'bottom' },
                        vAxis: {viewWindowMode: "explicit", viewWindow:{min:0}},
                    }

                    let chart = new google.visualization.LineChart(document.getElementById('bookings_chart'))

                    chart.draw(data, options)

                    let coversData = google.visualization.arrayToDataTable(@this.covers)

                    let coversOptions = {
                        title: 'Guests Comparison vs. Next/Last Week',
                        curveType: 'function',
                        legend: { position: 'bottom' },
                        vAxis: {viewWindowMode: "explicit", viewWindow:{min:0}},
                    }

                    let coversChart = new google.visualization.LineChart(document.getElementById('covers_chart'))

                    coversChart.draw(coversData, coversOptions);

                    let reviewsData = google.visualization.arrayToDataTable(@this.reviews)

                    let reviewsOptions = {
                        title: 'Monthly Reviews',
                        legend: { position: 'bottom' },
                        seriesType: 'line',
                        series: {0: {type: 'bars'}},
                        vAxis: {viewWindowMode: "explicit", viewWindow:{min:0}},
                        chartArea: {
                            top: 55,
                            height: '40%'
                        }
                    }

                    let reviewsChart = new google.visualization.ComboChart(document.getElementById('reviews_chart'))

                    reviewsChart.draw(reviewsData, reviewsOptions)
                }

                document.addEventListener("updated-restaurant", drawChart)
            })
        </script>
    @endpush
</div>
