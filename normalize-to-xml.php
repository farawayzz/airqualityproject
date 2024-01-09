<?php
# set timezone
@date_default_timezone_set("GMT");

ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');
ini_set('auto_detect_line_ending', TRUE);

$files = scandir("csv/");
print_r($files); 

for($f = 0; $f < count($files); $f++){

    if($files[$f] != ".." && $files[$f] != "."){

        // read uploaded file information
        $csv_filename = "csv/" .$files[$f];

        echo "<br><hr><br>Starting create csv " .$files[$f];

        $csv_num_rows = 0;
        $file = fopen($csv_filename, "r"); // read from the csv file

        $siteID = "";
        $loc = "";
        $geocode = "";

        // XML creation
        $xml = new SimpleXMLElement('<station/>');

        // loop through csv file & process message
        // example message in csv: 188,1084518000,73.0,42.0,20.0,14.0,,,,,,0.2,38.0,3.0,AURN Bristol Centre,51.4572041156,-2.58564914143
        while(! feof($file)) {
            $message = fgets($file);
            $message_arr = explode(",", $message); // break messsages by comma (,)

            if($message_arr[0] != "siteID" && $message_arr[0] != ""){

                if(empty($siteID)){
                    $siteID = $message_arr[0];
                    $xml -> addAttribute('id', $siteID);
                }

                if(empty($loc)){
                    $loc = $message_arr[14];
                    $xml -> addAttribute('name', $loc);
                }

                if(empty($geocode)){
                    $geocode = $message_arr[15].",".$message_arr[16];
                    $xml -> addAttribute('geocode', $geocode);
                }

                // looping process
                $ts = $message_arr[1];
                $nox = $message_arr[2];
                $no2 = $message_arr[3];
                $no = $message_arr[4];

                $node = $xml->addChild("rec");
                $node -> addAttribute('ts', $ts);
                $node -> addAttribute('nox', $nox);
                $node -> addAttribute('no', $no);
                $node -> addAttribute('no2', $no2);
            }
        }

        // $xml->asXML("xml/data-".$siteID.".xml");
        // echo "Done generate xml " .$siteID;


        //Format XML to save indented tree rather than one line
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        $dom->save("xml/data-".$siteID.".xml");
    }
}

?>