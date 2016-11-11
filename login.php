<?php
require_once 'class.citiesDAO.php';
$cdao = new CitiesDAO();
if($_SERVER["REQUEST_METHOD"] === 'POST'){
    if(isset($_POST['login']) & isset($_POST['password'])){
        if(!empty($_POST['login']) & !empty($_POST['password'])){
            //query database and if login == success sesstion_start()
            //redirect to search
            $login_id = $_POST['login'];
            $pass = $_POST['password'];
            if($cdao->check_login($login_id,$pass)){
                // start session
                // redirect to search_index.php
                session_start();
                $_SESSION['login_id'] = $login_id;
                echo "Session started";
                header("Location: ./search_index.php");

            }
            else {
                // login failed
                // if user exists in database, increase login count
                header('Location: ./index.php');
            }
        }
    }
}
 ?>
