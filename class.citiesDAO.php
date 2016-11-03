<?php
/**
 * This class is responsible for all database access
 */
class CitiesDAO{
    private $pdo;
    public function __construct(){
        $this->pdo = new PDO('mysql:host=korra.dawsoncollege.qc.ca;dbname=CS1432581','CS1432581', 'illifere');
    }

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



}
?>
