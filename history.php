<?php
header('content-type:application/json');
require_once 'class.citiesDAO.php';
$cdao = new CitiesDAO();
session_start();
session_regenerate_id();
$login_id = $_SESSION['login_id'];
if(isset($_POST['item'])){
    if(is_string($_POST['item'])){
        $city_name = $_POST['item'];
        
    }
}
 ?>
