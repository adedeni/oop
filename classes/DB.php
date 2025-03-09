<?php
class DB {
    private static $_instance = null;
    private $_pdo,
            $_query,
            $_error = false,
            $_results,
            $_count = 0,
            $_errorMessage = '';

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

public function query($sql, $params = []) {
    $this->_error = false;//this is to reset the error to false
    $this->_errorMessage = '';//this is to reset the error message to an empty string
    
    try {
        if($this->_query = $this->_pdo->prepare($sql)) {//this is to prepare the sql query
            $x = 1;
            if(count($params)) {
                foreach($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            
            if($this->_query->execute()) {//this is to execute the sql query
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);//this is to fetch all the results from the query
                $this->_count = $this->_query->rowCount();//this is to count the number of rows affected by the query
            } else {
                $this->_error = true;
                $this->_errorMessage = $this->_query->errorInfo()[2];
            }
        }
    } catch(PDOException $e) {
        $this->_error = true;
        $this->_errorMessage = $e->getMessage();
        error_log($e->getMessage());
    }
    return $this;
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

public function insert($table, $fields = []) {
    //try {//for debugging purposes
        $keys = array_keys($fields);
        $values = '';
        $x = 1;
        
        foreach($fields as $field) {
            $values .= '?';
            if($x < count($fields)) {
                $values .= ', ';
            }
            $x++;
        }
        
        $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";
        
        // Debug: Print the SQL query and values
        // echo "SQL Query: " . $sql . "<br>";
        // echo "Values: ";
        // var_dump(array_values($fields));
        
       // $query = $this->query($sql, $fields);//for debugging purposes with the try catch block
        if(!$this->query($sql, $fields)->error()){
            return true;
        }
        return false;
        // if($query->error()) {
        //     $this->_errorMessage = $query->_query->errorInfo()[2];
        //     echo "Database Error: " . $this->_errorMessage . "<br>";
        //     return false;
        // }
        // return true;
    // } catch(Exception $e) {
    //     $this->_errorMessage = $e->getMessage();
    //     echo "Exception: " . $this->_errorMessage . "<br>";
    //     return false;
    // }
}

public function update($table, $id, $fields = []) {
    $set = '';
    $x = 1;

    foreach($fields as $name => $value) {
        $set .= "`{$name}` = ?";//this is to add the name and value to the set
        if($x < count($fields)) {
            $set .= ', ';//this is to add a comma to the set
        }
        $x++;
    }

    $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
    
    if(!$this->query($sql, array_values($fields))->error()) {//this is to check if the query is successful
        return true;
    }
    return false;
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
public function getError() {
    return $this->_errorMessage;
}
}
