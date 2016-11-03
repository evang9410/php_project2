<?php
class City{
    private $name;
    private $country;
    private $weight;

    public function __construct($name="", $country="", $weight=-1){
        $this->name = $name;
        $this->country = $country;
        $this->weight = $weight;
    }

    public function getName(){
        return $this->name;
    }
    public function setName($name){
        $this->name = $name;
    }
    public function getCountry(){
        return $this->$country;
    }
    public function setCountry($country){
        $this->country = $country;
    }
    public function getWeight(){
        return $this->weight;
    }
    public function setWeight($weight){
        $this->weight = $weight;
    }
}
?>
