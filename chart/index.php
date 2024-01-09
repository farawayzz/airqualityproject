<html>
  <head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  </head>

  <style>

  .input-box{
    margin:0.5em 1em;
  }

  </style>

  <body>

    <div class="input-wrap">

      <div class="input-box">
        <label>Year: </label>
        <input id="index-year" value="2015" type="text">
      </div>

      <div class="input-box">
        <label>Time: </label>
        <input id="index-time" value="8" type="int">
      </div>


      <div class="input-box">
        <label>Stations: </label>
        <select id="index-station">
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

    <div id="chart_div" style="width: 900px; height: 500px;"></div>

    <script type="text/javascript">

        var $scatter_data = [];
        google.charts.load('current', {'packages':['corechart']});

        $(document).ready(function(){

          $('#generateChart').click(function(){
            var $station_selected = $('#index-station option:selected').val();
            var $time_selected = $('#index-time').val();
            var $year_selected = $('#index-year').val();

            console.log("Submit");
            console.log("Station: " + $station_selected);
            console.log("Time: " + $time_selected);
            console.log("Year: " + $year_selected);

            $.post('load-data-chart.php', {station: $station_selected, time: $time_selected, year: $year_selected}, function(response){
                  console.log(response);
                  $scatter_data = JSON.parse(response);
                  console.log($scatter_data);

                  google.charts.setOnLoadCallback(drawChart);
              });
            });
            
        });

        function drawChart() {
          var data = google.visualization.arrayToDataTable($scatter_data);

          var options = {
          title: 'Average Carbon Monoxide index',
          hAxis: {title: 'Month', minValue: 0, maxValue: 12},
          vAxis: {title: 'Carbon Monoxide', minValue: 0, maxValue: 15},
          legend: 'none'
          };

          var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));
          chart.draw(data, options);
        }

    </script>

  </body>
</html>