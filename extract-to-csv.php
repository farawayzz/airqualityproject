<?php

# set timezone
@date_default_timezone_set("GMT");

ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');
ini_set('auto_detect_line_ending', TRUE);

// $csv_filename = $_FILES["csv_file"]["name"];
$csv_filename = "air-quality-data-2004-2019.csv";
    
// count csv rows
$csv_num_rows = 0;

// file csv array
$site_arr  = ['188', '203', '206', '209', '213', '215', '228', '270', '271', '375', '395', '452', '447', '459', '463', '481', '500', '501'];
$csv_arr = [];

//create tree index
for($a = 0; $a < count($site_arr); $a++){
    array_push($csv_arr, array("site" => $site_arr[$a], "csv" => []));
}

// echo "<pre>".print_r($csv_arr,true)."</pre>";

// save csv header
$content = "siteID,ts,nox,no2,no,pm10,nvpm10,vpm10,nvpm2.5,pm2.5,vpm2.5,co,o3,so2,loc,lat,long\n";
for($i = 0; $i < count($site_arr); $i++){
    $file_c = "csv/data-".$site_arr[$i].".csv";

    if(!is_file($file_c)){
        file_put_contents($file_c, $content);
    }
}

$file = fopen($csv_filename, "r");
while(! feof($file)) {

    $line = "";
    $line_csv = "";

    $line = fgets($file);
    $line_arr = explode(";", $line);

    if((count($line_arr) >= 22) && $line_arr[4] != "SiteID" && ($line_arr[0] != 0 || $line_arr[0] != '0' )){

        try{
            $geo_2d = explode(",", $line_arr[18]);
            $date_ts = new DateTime($line_arr[0]);

            $siteID = $line_arr[4];
            $ts = $date_ts->getTimestamp();
            $nox = $line_arr[1];
            $no2 = $line_arr[2];
            $no = $line_arr[3];
            $pm10 = $line_arr[5];
            $nvpm10 = $line_arr[6];
            $vpm10 = $line_arr[7];
            $nvpm2p5 = $line_arr[8];
            $pm2p5 = $line_arr[9];
            $vpm2p5 = $line_arr[10];
            $co = $line_arr[11];
            $o3 = $line_arr[12];
            $so2 = $line_arr[13];
            $loc = $line_arr[17];
            $lat = $geo_2d[0];
            $long = $geo_2d[1];

            $line_csv = array($siteID, $ts, $nox, $no2, $no, $pm10, $nvpm10, $vpm10, $nvpm2p5, $pm2p5, $vpm2p5, $co, $o3, $so2, $loc, $lat, $long);
            $line_csv_str = implode(",", $line_csv). "\n";

            if(!empty($nox) || !empty($co)){
                switch($siteID){
                    case "188": array_push($csv_arr[0]['csv'], $line_csv_str); break;
                    case "203": array_push($csv_arr[1]['csv'], $line_csv_str); break;
                    case "206": array_push($csv_arr[2]['csv'], $line_csv_str); break;
                    case "209": array_push($csv_arr[3]['csv'], $line_csv_str); break;
                    case "213": array_push($csv_arr[4]['csv'], $line_csv_str); break;
                    case "215": array_push($csv_arr[5]['csv'], $line_csv_str); break;
                    case "228": array_push($csv_arr[6]['csv'], $line_csv_str); break;
                    case "270": array_push($csv_arr[7]['csv'], $line_csv_str); break;
                    case "271": array_push($csv_arr[8]['csv'], $line_csv_str); break;
                    case "375": array_push($csv_arr[9]['csv'], $line_csv_str); break;
                    case "395": array_push($csv_arr[10]['csv'], $line_csv_str); break;
                    case "452": array_push($csv_arr[11]['csv'], $line_csv_str); break;
                    case "447": array_push($csv_arr[12]['csv'], $line_csv_str); break;
                    case "459": array_push($csv_arr[13]['csv'], $line_csv_str); break;
                    case "463": array_push($csv_arr[14]['csv'], $line_csv_str); break;
                    case "481": array_push($csv_arr[15]['csv'], $line_csv_str); break;
                    case "500": array_push($csv_arr[16]['csv'], $line_csv_str); break;
                    case "501": array_push($csv_arr[17]['csv'], $line_csv_str); break;
                }
            }
        }
        catch(Exception $e){
            echo "<br>Error message : ". $e;
            echo "<hr><pre>".print_r($line_arr,true)."</pre>";
            echo "<br><br>The line? = " .$csv_num_rows. " | ".$line;
        }

        $csv_num_rows++;
    }
}

fclose($file);
echo "<br><hr>".$csv_num_rows;

// Save csv string into csv file
for($f = 0; $f < count($csv_arr); $f++){

    $file_csv = "csv/data-" .$csv_arr[$f]["site"]. ".csv";
    $file2 = fopen($file_csv,'a');

    echo "<hr><br>Starting load " .$csv_arr[$f]["site"];

    //loop
    echo "<br>" .$csv_arr[$f]["site"]. " -> " .count($csv_arr[$f]["csv"]);

    for($l = 0; $l < count($csv_arr[$f]["csv"]); $l++){
        fwrite($file2, $csv_arr[$f]["csv"][$l]);
    }
    
    fclose($file2);
    echo "<br>Done.. load " .$csv_arr[$f]["site"];
}

?>