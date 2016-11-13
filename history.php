<?php
header('content-type:application/json');
require_once 'class.citiesDAO.php';
$cdao = new CitiesDAO();
session_start();
session_regenerate_id();
$login_id = $_SESSION['login_id'];
$user_history = $cdao->get_user_history($login_id);
echo json_encode($user_history);
 ?>
