<?php
header('content-type:application/json');
require_once 'class.citiesDAO.php';
$cdao = new CitiesDAO();
if(isset($_GET['q'])){
    if($_GET['q'] !== ""){
        $key = $_GET['q'];
        $cities = $cdao->search_cities($key);
        $json_array = array("cities" => $cities); // creates a named array "cities" for simpler json parsing on the js side.
        echo json_encode($json_array);
    }

}else{
    echo "failed";
}



 ?>
