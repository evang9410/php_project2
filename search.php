<?php
require_once 'class.citiesDAO.php';
$cdao = new CitiesDAO();
var_dump($cdao->search_cities("Mon"));
 ?>
