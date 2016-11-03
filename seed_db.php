<?php
$file = fopen("cities.txt", "r");
require_once("class.citiesDAO.php");
$cDAO = new CitiesDAO();
$count = 0;
while(!feof($file)){
    $line = fgets($file);
    $weight = substr($line, 0, strrpos($line,';'));
    $csv = explode(',', substr($line,strrpos($line,';')+1));
    $length = count($csv);
    $country = trim($csv[$length -1]);
    $city = implode(",",array_slice($csv,0,-1));
    $cDAO->insert_city($city,$country,$weight);
    $count++;
    if($count % 1000 == 0){
        echo "$count ";
    }
}
?>
