<?php
/**
 * This class is responsible for all database access
 */
require_once("class.City.php");
require_once("class.User.php");
class CitiesDAO{
    private $pdo;
    public function __construct(){
        $this->pdo = new PDO('mysql:host=korra.dawsoncollege.qc.ca;dbname=CS1432581','CS1432581', 'illifere');
    }
    /**
     * Inserts the city data into the database.
     */
    public function insert_city($name, $country, $weight){
        $insert_query = 'INSERT INTO cities(name, country, weight) VALUES(?,?,?)';
        try{
            $stmt = $this->pdo->prepare($insert_query);
            $stmt->bindParam(1,$name);
            $stmt->bindParam(2,$country);
            $stmt->bindParam(3,$weight);
            $stmt->execute();
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    /*
     * Returns an array of City objects based on the search term limiting 5
     * results.
     */
    public function search_cities($key){
        $query = 'SELECT * FROM cities WHERE name LIKE ? LIMIT 5';
        $cities = [];
        $key = $key."%";
        try{
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(1,$key);
            $stmt -> setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'City');
            $stmt -> execute();
            while($city = $stmt->fetch()){
                $cities[] = $city;
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $cities;
    }

    /**
     * Verify if password matches hashed password in the database
     * returns true if user is successfully logged in.
     */
    public function check_login($login_id, $pass){
        $success = false;
        $query_hashed_pass = 'SELECT * FROM users WHERE login_id = ?';
        // get user object containing the hased password.
        try{
            $stmt = $this->pdo->prepare($query_hashed_pass);
            $stmt->bindParam(1,$login_id);
            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User');
            $stmt -> execute();
            $user = $stmt->fetch();
            if(!$user){
                return $success;
            }
            // check if user count is greater than 5
            $hashed_pass = $user->getHashedPass();
            if(password_verify($pass,$hashed_pass)){
                $success = true;
                // reset user count to zero
                // increment last_login
            }else{ // if login is unsuccessfull
                $count = $this->increase_user_count($user);
                //redirect to login page.

            }
        }catch(PDOEXception $e){
            echo $e->getMessage();
        }

        return $success;
    }

    /**
     * Returns the users login_count after incrementing it in the database.
     */
    public function increase_user_count($user){
        $login_count = $user->getLoginCount();
        $query = 'UPDATE users SET last_login = ? WHERE login_id = ?';
        try{
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(1,++$login_count);
            $stmt->bindParam(2,$user->getLoginId());
            $stmt->execute();
        }catch(PDOEXception $e){
            echo $e->getMessage();
        }
        return $login_count;
    }
    /**
     * Registers the user in the database.
     * @return: Returns if register was success or not.
     * @param: $user --> user object to be inserted into the database
     *
     */
    public function register_user($user){
        if(!$user){
            return false;
        }
        $login_id = $user->getLoginId();
        $hashed_pass = $user->getHashedPass();
        if(!$this->user_exists($user->getLoginId())){
            // insert user into database
            $query = 'INSERT INTO users (login_id,hashed_pass) VALUES(?,?)';
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(1, $login_id);
            $stmt->bindParam(2,$hashed_pass);
            $stmt->execute();
            return true;
        }else{ // return false.
            return false;

        }

    }
    /**
     * Returns true if the user exists in the database, returns false if
     * login_id is unique
     */
    public function user_exists($login_id){
        $query = "SELECT login_id FROM users WHERE login_id = $login_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        if(!$stmt->fetch()){ // if no result set, user is unique
            return false;
        }else{
            return true;
        }
    }

}
?>
