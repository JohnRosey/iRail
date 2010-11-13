<?php

/**
 * This class has to be a provider for all Stations available
 *
 * @author pieterc
 */
ini_set("include_path", ".:../:api/DataStructs:DataStructs:api/:../includes:includes");
include_once("Input.php");
include_once("DataStructs/Station.php");
class StationsInput extends Input {

    protected function fetchData(Request $request) {
        $country = strtoupper($request->getCountry());
        $lang = strtoupper($request->getLang());
        $stations = array();
        $count = 0;
        $pre = "";
        $arr = explode("/" ,  $_SERVER['PHP_SELF']);
        if($arr[sizeof($arr)-2] == "api"){
            $pre = "../";
        }
        if (($handle = fopen($pre . "stationlists/" . $country . "_" . $lang . ".csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $stations[$count] = new Station($data[0], $data[2], $data[1]);
                $count++;
            }
            fclose($handle);
        }
        return $stations;
    }

    protected function transformData($serverData) {
        return $serverData;
    }

    public function generate_js_array($request) {
        $stations = $this->fetchData($request);
        $output = '';
        foreach ($stations as $i => $station) {
            $output .= '"' . $station->getName() . '",';
        }
        $output = rtrim($output, ',');
        return $output;
    }

}
?>
