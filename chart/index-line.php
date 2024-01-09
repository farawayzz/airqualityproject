<html>

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<style>
.input-box {
    margin: 0.5em 1em;
}
</style>

<body>

    <div class="input-wrap">

        <div class="input-box">
            <label>Date: </label>
            <input id="index-date" type="date" value="2015-01-01">
        </div>

        <div class="input-box">
            <label>Pollutants: </label>
            <select id="index-poll">
                <option value="nox">NOx - Concentration of oxides of nitrogen</option>
                <option value="no">NO - Concentration of nitric oxide</option>
                <option value="no2">NO2 - Concentration of nitrogen dioxide</option>
            </select>
        </div>


        <div class="input-box">
            <label>Stations: </label>
            <select id="index-station" multiple>
                <option value="188">188 - AURN Bristol Centre</option>
                <option value="203">203 - Brislington Depot</option>
                <option value="206">206 - Rupert Street</option>
                <option value="209">209 - IKEA M32</option>
                <option value="213">213 - Old Market</option>
                <option value="215">215 - Parson Street School</option>
                <option value="228">228 - Temple Meads Station</option>
                <option value="270">270 - Wells Road</option>
                <option value="271">271 - Trailer Portway P&R</option>
                <option value="375">375 - Newfoundland Road Police Station</option>
                <option value="395">395 - Shiner's Garage</option>
                <option value="452">452 - AURN St Pauls</option>
                <option value="447">447 - Bath Road</option>
                <option value="459">459 - Cheltenham Road \ Station Road</option>
                <option value="463">463 - Fishponds Road</option>
                <option value="481">481 - CREATE Centre Roof</option>
                <option value="500">500 - Temple Way</option>
                <option value="501">501 - Colston Avenue</option>
            </select>
        </div>

        <div class="input-box"><button id="generateChart">Submit</button></div>

    </div>

    <div id="chart_div" style="width: 1000px; height: 600px;"></div>

    <script type="text/javascript">

    var $scatter_data = [];
    var $station_selected = [];
    var $pollutants;

    google.charts.load('current', {
        'packages': ['corechart']
    });

    $(document).ready(function() {

    
        $('#generateChart').click(function() {

            var $index_date = $('#index-date').val();
            $pollutants =  $('#index-poll').val();
            $station_selected = [];

            $('#index-station option:selected').each(function(index) {
                $station_selected.push($(this).val());
            });

            console.log($station_selected);
            console.log("Time: " + $index_date);

            $.post('load-data-chart-line.php', { station: $station_selected, pollutants: $pollutants, date: $index_date }, function(response) {
                console.log(response);

                if(response != "Failed"){
                    $scatter_data = JSON.parse(response);
                    
                    console.log($scatter_data);
                    google.charts.setOnLoadCallback(drawLineColors);
                }
                
            });
        });

    });

    function drawLineColors() {
        var data = new google.visualization.DataTable();

        data.addColumn('number', 'X');

        for(i = 0; i < $station_selected.length; i++){
            data.addColumn('number', $station_selected[i]);
        }

        data.addRows($scatter_data);

        var options = {
            hAxis: {
                title: 'Hour',
                gridlines: {minSpacing: 24}
            },
            vAxis: {
                title: 'Carbon'
            },
            colors: ['black', 'blue', 'red', 'green', 'yellow', 'gray', 'purple', 'seagreen']
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }

    </script>

</body>

</html>