<div class="space-y-10">
    <div>
        <x-select wire:model="restaurants">
            @foreach($restaurants as $r)
                <option value="{{ $r->id }}">{{ $r }}</option>
            @endforeach
        </x-select>
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
            document.addEventListener('livewire:load', () => {
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = google.visualization.arrayToDataTable(@this.bookings);

                    var options = {
                        title: 'Booking Comparison vs. Last Week',
                        curveType: 'function',
                        legend: { position: 'bottom' },
                        vAxis: {viewWindowMode: "explicit", viewWindow:{min:0}},
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('bookings_chart'));

                    chart.draw(data, options);

                    var coversData = google.visualization.arrayToDataTable(@this.covers);

                    var coversOptions = {
                        title: 'Guests Comparison vs. Last Week',
                        curveType: 'function',
                        legend: { position: 'bottom' },
                        vAxis: {viewWindowMode: "explicit", viewWindow:{min:0}},
                    };

                    var coversChart = new google.visualization.LineChart(document.getElementById('covers_chart'));

                    coversChart.draw(coversData, coversOptions);

                    console.log(@this.reviews);
                    var reviewsData = google.visualization.arrayToDataTable(@this.reviews);

                    var reviewsOptions = {
                        title: 'Monthly Reviews',
                        legend: { position: 'bottom' },
                        seriesType: 'line',
                        series: {0: {type: 'bars'}},
                        vAxis: {viewWindowMode: "explicit", viewWindow:{min:0}},
                        chartArea: {
                            top: 55,
                            height: '40%'
                        }
                    };

                    var reviewsChart = new google.visualization.ComboChart(document.getElementById('reviews_chart'));

                    reviewsChart.draw(reviewsData, reviewsOptions);
                }
            })
        </script>
    @endpush
</div>
