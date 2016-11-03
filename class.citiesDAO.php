<?php
/**
 * This class is responsible for all database access
 */
require_once("class.City.php");
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
            return $cities;
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }



}
?>
