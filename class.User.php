<?php
class User{
    private $login_id;
    private $hashed_pass;
    private $login_count;
    private $last_login;

    public function __construct($login_id = "", $hashed_pass = "", $login_count = 1){
        $this->login_id = $login_id;
        $this->hashed_pass = $hashed_pass;
        $this->login_count = $login_count;
        $this->last_login = time();
    }

    public function getLoginId(){
        return $this->login_id;
    }
    public function setLoginId($login_id){
        $this->login_id = $login_id;
    }

    public function getHashedPass(){
        return $this->hashed_pass;
    }
    public function setHashedPass($hashed_pass){
        $this->hashed_pass = $hashed_pass;
    }

    public function getLoginCount(){
        return $this->login_count;
    }
    public function setLoginCount($count){
        $this->login_count = $count;
    }
}
 ?>
