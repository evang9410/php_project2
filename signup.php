<?php
require_once('class.User.php');
require_once 'class.citiesDAO.php';
$cdao = new CitiesDAO();

if($_SERVER["REQUEST_METHOD"] === 'POST'){
    if(isset($_POST['register_login']) & isset($_POST['register_password'])){
        if(!empty($_POST['register_login']) & !empty($_POST['register_password'])){
            // register user if login doesnt exist.
            $login_id = htmlentities($_POST['register_login']);
            if(!$cdao->user_exists($login_id)){
              $login_id = htmlentities($_POST['register_login']);
              $pass = htmlentities($_POST['register_password']);
              $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
              $user = new User($login_id,$hashed_pass);
              $cdao->register_user($user);
              session_start();
              $_SESSION['login_id'] = $login_id;
              header('location: ./search_index.php');
            }else{
              echo "user id is taken";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="./styles/style.css">
</head>
<body id="signup">
    <div class="container-fluid">
        <div class="row">
            <h2 id = "signup_header"> Sign up for our super awesome website </h2>
            <div class="col-md-3 center-block" id="signup_form">

                <form action="" method="POST">
                  <div class="form-group">
                    <label id="signup_text">Login id:</label>
                    <input type="text" name="register_login"class="form-control">
                  </div>
                  <div class="form-group">
                    <label id="signup_text" for="pwd">Password:</label>
                    <input type="password" name="register_password" class="form-control" id="pwd">
                  </div>
                  <button type="submit" class="btn btn-default">Register now!</button>
                </form>
            </div>
        </div>
    </div>
