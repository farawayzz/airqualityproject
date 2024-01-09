<?php

# set timezone
@date_default_timezone_set("GMT");

ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');
ini_set('auto_detect_line_ending', TRUE);

$time_start = microtime(true); 

$file = "../xml/data-".$_POST["station"].".xml";
$year = $_POST["year"];
$hour = $_POST["time"];


$index_arr = [];
$month_arr = [];

for($m = 0; $m < 12; $m++){
    array_push($index_arr, array(0,0));
    array_push($month_arr, $m + 1);
}

$reader = new XMLReader();
// $reader->open("../xml/data-209.xml")
if (!$reader->open($file))
{
    die("Failed to open 'data.xml'");
}
while($reader->read())
{
    // $node = $reader->expand();
    if($reader->name == "rec"){

        $datetime = date('m/d/Y H:i:s', (int)$reader->getAttribute("ts"));
        $yearx = date('Y', (int)$reader->getAttribute("ts"));
        $monthx = date('m', (int)$reader->getAttribute("ts"));
        $hourx = date('H', (int)$reader->getAttribute("ts"));

        if($hourx == $hour && $yearx == $year){
            $index_arr[$monthx-1][0]=$index_arr[$monthx-1][0]+1;
            $index_arr[$monthx-1][1]+=$reader->getAttribute("no");
        }
    }
}
$reader->close();


$average_idx_arr = [];

for($a = 0; $a < count($index_arr); $a++){

    if($index_arr[$a][0] > 0){
        $average_idx = $index_arr[$a][1] / $index_arr[$a][0];
    }else{
        $average_idx = $index_arr[$a][1];
    }
    
    array_push($average_idx_arr, $average_idx);
}


$json_response = [];

for($m = 0; $m < 12; $m++){

    if($m == 0) array_push($json_response, array('Month', 'Carbon Monoxide'));
    array_push($json_response, array($month_arr[$m],$average_idx_arr[$m]));
}

echo $personJSON=json_encode($json_response);

$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;
// echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';

?>