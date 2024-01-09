<?php

# set timezone
@date_default_timezone_set("GMT");

ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');
ini_set('auto_detect_line_ending', TRUE);

$time_start = microtime(true); 

$station_arr = $_POST["station"];
$pollutants = $_POST["pollutants"];
// $station_arr = array("213", "271", "447");
$date = date("yyyy-mm-dd", strtotime($_POST["date"]));

$json_response_all = [];

// if($date >= date("yyyy-mm-dd", strtotime("2015-01-01")) &&  $date <= date("yyyy-mm-dd", strtotime("2019-12-31"))){
    for($s = 0; $s < count($station_arr); $s++){
        
        
    
        // echo "<br><hr>";
        $file = "../xml/data-".$station_arr[$s].".xml";
        // echo "<br>";
    
        $index_arr = [];
        $hour_arr = [];
    
        for($i = 0; $i < 24; $i++){
            array_push($index_arr, array(0,0));
            array_push($hour_arr, $i);
        }
    
        $reader = new XMLReader();
        // $reader->open("../data-209.xml")
        if (!$reader->open($file))
        {
            die("Failed to open ".$file."'data.xml'");
        }
        while($reader->read())
        {
            // $node = $reader->expand();
            if($reader->name == "rec"){
    
                $datetime = date('d/m/Y H:i:s', (int)$reader->getAttribute("ts"));
                // $datex = (int)$reader->getAttribute("ts");
                $datex = date("yyyy-mm-dd", (int)$reader->getAttribute("ts"));
                $hourx = date('G', (int)$reader->getAttribute("ts"));
    
                if($datex == $date){
                    if($hourx == 0){
                        $index_arr[0][0]=$index_arr[0][0];
                        $index_arr[0][1]+=$reader->getAttribute($pollutants);
                    }else{
                        $index_arr[$hourx][0]=$index_arr[$hourx][0];
                        $index_arr[$hourx][1]+=$reader->getAttribute($pollutants);
                    }
    
            
                    
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
    
        
    
        for($h = 0; $h < 24; $h++){
            if($h == 0){
                array_push($json_response, array('Hour', 'Carbon Monoxide'));
            }
    
            if($s == 0){
                $json_response_all[$h][0] = $h;
            }
    
            array_push($json_response, array($hour_arr[$h],$average_idx_arr[$h]));
            $json_response_all[$h][$s+1] = $average_idx_arr[$h];
        }
    
        
    }
    
    $time_end = microtime(true);
    $execution_time = ($time_end - $time_start)/60;
    // echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';
    
    
    echo $personJSON=json_encode($json_response_all);


?>