<?php
header('content-type:application/json'); 
require_once 'class.citiesDAO.php';
$cdao = new CitiesDAO();
session_start();
session_regenerate_id();
$login_id = $_SESSION['login_id'];
 ?>
