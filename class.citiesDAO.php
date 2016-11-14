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
        $query = 'SELECT * FROM cities WHERE name LIKE ? ORDER BY weight DESC LIMIT 5';
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
            // creates the user object.
            $user = $stmt->fetch();
            if(!$user){ // user doesn't exist.
                return $success;
            }
            var_dump($user);

            $hashed_pass = $user->getHashedPass();
            if(password_verify($pass,$hashed_pass)){
                $success = true;
                // reset user count to zero
                // update last_login
            }else{ // if login is unsuccessfull
                $count = $this->increase_user_count($user);
                // check if user count is greater than 5
                if($count >= 5){
                    // timeout user.
                    $this->timeout_user($user->getLoginId());
                }
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
        $query = 'UPDATE users SET login_count = ? WHERE login_id = ?';
        try{
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1,++$login_count);
            $stmt->bindValue(2,$user->getLoginId());
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
     **/
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
        $query = "SELECT login_id FROM users WHERE login_id =?";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(1,$login_id);
        $stmt->execute();
        $rows = count($stmt->fetchAll(PDO::FETCH_ASSOC));
        if($rows == 0){
          return false;
        }else{
          return true;
        }
    }
    /**
     * Gets the users search history and returns it in an associtative array.
     */
    public function get_user_history($login_id){
        $q = 'SELECT DISTINCT city_name FROM history WHERE login_id = ? ORDER BY id DESC LIMIT 5';
        try{
            $stmt = $this->pdo->prepare($q);
            $stmt->bindParam(1,$login_id);
            $stmt->execute();
            return $user_history = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    /**
     * Inserts the city into the users history.
     */
    public function insert_user_history($login_id, $city){
        $insert_query = 'INSERT INTO history(login_id, city_name) VALUES (?, ?)';
        try{
            $stmt = $this->pdo->prepare($insert_query);
            $stmt->bindParam(1,$login_id);
            $stmt->bindParam(2,$city);
            $stmt->execute();
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    /**
     * Returns true if the user has is timed out
     **/
    public function is_user_timed_out($login_id){
        try{
            $stmt = $this->pdo->prepare('SELECT is_timedout FROM users WHERE login_id = ?');
            $stmt->bindParam(1,$login_id);
            $stmt->execute();
            return $stmt->fetch()[0];
        }catch(PDOException $e){
            $e->getMessage();
        }
    }

    /**
     * Time out the user, set timeout time to now + 5mins
     * Set is_timeout to true
     **/
     public function timeout_user($login_id){
         $update_query = 'UPDATE users SET timeout = ?, is_timedout = ? WHERE login_id = ?';
         $timeout = date('Y-m-d H:i:s',time() + 300);
         var_dump($timeout);
         try{
             $stmt = $this->pdo->prepare($update_query);
             $stmt->bindValue(1,$timeout);
             $stmt->bindValue(2, true);
             $stmt->bindValue(3, $login_id);
             $stmt->execute();
         }catch(PDOException $e){
             $e->getMessage();
         }
     }

     public function free_user($login_id){
         $update = 'UPDATE users SET timeout = ?, is_timedout = ?, last_login = ?, login_count = ? WHERE login_id = ?';
         $last_login = date('Y-m-d H:i:s',time());
         try{
             $stmt = $this->pdo->prepare($update);
             $stmt->bindValue(1, null);
             $stmt->bindValue(2, false);
             $stmt->bindValue(3, $last_login);
             $stmt->bindValue(4, 0);
             $stmt->bindValue(5, $login_id);
             $stmt->execute();
         }catch(PDOException $e){
             $e->getMessage();
         }
     }

     public function get_user_timeout_time($login_id){
         $q = 'SELECT timeout FROM users WHERE login_id = ?';
         try{
             $stmt = $this->pdo->prepare($q);
             $stmt->bindValue(1, $login_id);

             $stmt->execute();
             return $stmt -> fetch()[0];
         }catch(PDOException $e){
             return -1;
         }
     }

}
?>
