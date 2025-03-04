<?php
class DB {
    private static $_instance = null;
    private $_pdo,
            $_query,
            $_error = false,
            $_results,
            $_count = 0;  

private function __construct() {//contructor is used to run the operation of the class at every instantiating
    try {
        $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), config::get('mysql/username'), Config::get('mysql/password'));
        //echo 'this DB is  connected';//this is to check if the connection is successful
        $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        $this->_error = true;
        error_log($e->getMessage());
        return;
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
    $this->_error = false; //reset the error back to false, because we can perform multiple queries and we do not want to return the error of the previous query
    try {
        if($this->_query = $this->_pdo->prepare($sql)){//check if query was prepared successfully
            //echo "query prepared success";//this is to check if the query was prepared successfully
            //exit ();
            $x = 1;//this is the position of the parameter and $param is the value of the parameter
            if(count($params)){
                foreach($params as $param){
                    $this->_query->bindValue($x, $param);
                    $x++;//this is to increment the position of the parameter for each loop
                }
            }
            //this is to execute the query even when there is no parameter, just execute the query anyway
            if($this->_query->execute()){
                //echo "query executed successfully";//this is to check if the query was executed successfully
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);//store the results in the $_results property
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;//set the error to true if the query is not executed successfully
            }
        }
    } catch(PDOException $e) {
        $this->_error = true;
        error_log($e->getMessage());
    }
    return $this;//this is to return the object of the class
}
public function action($action, $table, $where = []){//this is to perform any action on the datebase, e.g get, delete, update, insert
    if(count($where) === 3){
        $operators = ['=', '>', '<', '>=', '<='];//this are the operators that can be used in the query
        $field = $where[0];
        $operator = $where[1];
        $value = $where[2];
        if(in_array($operator, $operators)){//to check if the operator is in the array of operators
            $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";//this is just like writing "(SELECT * =$action)FROM (users =$table)WHERE (=$field) (= =$operator) '(adedeni= value in this case ?)'"
            if(!$this->query($sql, [$value])->error()){
                return $this;
            }
        }
    }
    return false;
}
public function get($table, $where){//this is to get all data from the database
    return $this->action('SELECT *', $table, $where);
}
public function delete($table, $where){//this is to delete data from the database
    return $this->action('DELETE', $table, $where);
}
public function results(){//this method returns the results of the query as an array
    return $this->_results;
}
public function first(){//this method returns the first result of the query as an object
    return $this->results()[0];
}
public function error(){
    return $this->_error;//this by default returns false and if there has beenn error this method will return true, remember that the error property is set to false by default at the top of the class
}
public function count(){//this method returns the number of rows affected by the query, or results returned by the query
   return $this->_count;
}

}