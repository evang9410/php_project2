<?
Class City{
    private $weight;
    private $name;
    private $country;

    public function __construct($name, $country, $weight){
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
        return $this->country;
    }
    public function setWeight($w){
        $this->weight = $w
    }
}
?>
