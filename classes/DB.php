<?php
class DB {
    private static $_instance = null;
    private $_pdo,
            $_query,
            $_error = false,
            $_results,
            $_count = 0;  

private function __construct() {
    try{
        $this->_pdo = new PDO('mysql:host=' . config::get('mysql/host') . ';dbname=' . config::get('mysql/db'), config::get('mysql/username'), config::get('mysql/password'));
        echo '<br>  Connected';
    } catch(PDOException $e){
        die($e->getMessage());
    }
}
//THIS IS TO CHECK IF WE HAVE INSTANTIATE THE OBJECT AND INSTANTIATE IF NOT
public static function getInstance() {
    if (!isset(self::$_instance)) {
        self::$_instance = new DB();
    }
    return self::$_instance;
}
public function query($sql, $params = []){
    $this->_error = false;
    if($this->_query = $this->_pdo->prepare($sql)){
        $x = 1;
        if(count($params)){
            foreach($params as $param){
                $this->_query->bindValue($x, $param);
                $x++;
            }
        }
        if($this->_query->execute()){
            $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
            $this->_count = $this->_query->rowCount();
        } else {
            $this->_error = true;
        }
    }
    return $this;
}
}