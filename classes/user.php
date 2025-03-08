<?php
class User{
    private $_db,
    $_data,
    $_sessionName,
    $_cookieName;

    public function __construct($user = null){
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');//this is to get the session name from the init.php file
        if(!$user){//
            if(Session::exists($this->_sessionName)){
                $user = Session::get($this->_sessionName);
            }else{
                $user = null;
            }
        }
        $this->_cookieName = Config::get('remember/cookie_name');//this is to get the cookie name from the ini file
    }
    public function create($fields = []) {
        try {
            // Debug: Print SQL query before execution
            //echo "Attempting database insertion...<br>";//for debugging purposes
            
            if(!$this->_db->insert('users', $fields)) {
                // Get the specific database error
                $dbError = $this->_db->getError();
                throw new Exception('Database Error: ' . $dbError);
            }
            return true;
        } catch(Exception $e) {
            throw $e; // Re-throw the exception with the database error
        }
    }
    
    // Add this method to get DB errors
    public function getLastError() {
        return $this->_db->getError();
    }

    public function find($user = null){
        if($user){
            $field = (is_numeric($user)) ? 'id' : 'username';//if the user is a number, then the field is id, otherwise it is username
            $data = $this->_db->get('users', [$field, '=', $user]);//get the data from where what was submitted in the form is equal to the field in the database
            if($data->count()){
                $this->_data = $data->first();//if the data is found, then set the data to the first row of the data gotten from the database
                return true;
            }
        }
        return false;
    }
    public function login($username = null, $password = null){//set the variables to null by default
        $user = $this->find($username);
        // print_r($this->_data);//for debugging purposes to see if the data is found
        // die();
        if($user){
            if($this->data()->password === Hash::make($password, $this->data()->salt)){//this is to check the hashed password in the database with the password submitted in the form
                // echo "Password is correct";
                // die();
                Session::put($this->_sessionName, $this->data()->id);  //this is to put the user id in the session
                return true;
            }
        }
        return false;
    }
    public function data(){
        return $this->_data;
    }
}