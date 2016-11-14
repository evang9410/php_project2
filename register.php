<?php
require_once('class.User.php');
require_once 'class.citiesDAO.php';
$cdao = new CitiesDAO();
if($_SERVER["REQUEST_METHOD"] === 'POST'){
    if(isset($_POST['register_login']) & isset($_POST['register_password'])){
        if(!empty($_POST['register_login']) & !empty($_POST['register_password'])){
            // register user if login doesnt exist.
            if(!$cdao->user_exists($_POST['register_login'])){
              $login_id = $_POST['register_login'];
              $hashed_pass = password_hash($_POST['register_password'], PASSWORD_DEFAULT);
              if(strlen($login_id) > 255){
                $login_id = substr($login_id,0,255);
              }
              $user = new User($login_id,$hashed_pass);
              $cdao->register_user($user);
              session_start();
              $_SESSION['login_id'] = $login_id;
              header('location: ./search_index.php');
            }else{
                // user exists.
                header('location: ./signup.html');
            }
        }
    }
}
header('location: ./signup.html');
?>
