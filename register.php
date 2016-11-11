<?php
require_once('class.User.php');
require_once 'class.citiesDAO.php';
$cdao = new CitiesDAO();
if($_SERVER["REQUEST_METHOD"] === 'POST'){
    if(isset($_POST['register_login']) & isset($_POST['register_password'])){
        if(!empty($_POST['register_login']) & !empty($_POST['register_password'])){
            // register user
            $hashed_pass = password_hash($_POST['register_password'], PASSWORD_DEFAULT);
            $login_id = $_POST['register_login'];
            $user = new User($login_id,$hashed_pass);

            //var_dump($user);
            // Insert into user table.
            // if register_user returns true, redirect to search_index.php
            // else redirect to register with login_id taken message
            if($cdao->register_user($user)){
                session_start();
                header('location: ./search_index.php');
            }else{
                header('location: ./signup.html');
            }


        }
    }
}
?>
