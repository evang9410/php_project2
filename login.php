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
            $timeout = $cdao->get_user_timeout_time($login_id);

            if($timeout != false){
                if(time() > strtotime($timeout)){
                    $cdao -> free_user($login_id);
                }else{
                    // redirect to timeout page
                    header('Location: ./timeout.html');
                }
            }
            if(!$cdao->is_user_timed_out($login_id)){
                if($cdao->check_login($login_id,$pass)){ // returns true if credentials are valid.
                    $cdao->free_user($login_id);
                    // start session
                    // redirect to search_index.php
                    session_start();
                    $_SESSION['login_id'] = $login_id;
                    //echo "Session started";
                    header("Location: ./search_index.php");
                }
                else {
                    // login failed
                    // if user exists in database, increase login count
                    // (done in the check_login, no need to do it here.)
                    header('Location: ./index.php');
                }
            }
        }else{
            header('Location: ./index.php');
        }
    }
}
 ?>
