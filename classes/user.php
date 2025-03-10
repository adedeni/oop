<?php
class User{
    private $_db,
    $_data,
    $_sessionName,
    $_cookieName,
    $_isLoggedIn;

    public function __construct($user = null){
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');//this is to get the session name from the init.php file
        $this->_cookieName = Config::get('remember/cookie_name');//this is to get the cookie name from the ini file
        if(!$user){
            if(Session::exists($this->_sessionName)){
                $user = Session::get($this->_sessionName);
                if($this->find($user)){//this is to help us grab the data of the user that is logged in
                    $this->_isLoggedIn = true;
                } else {
                    // Log out current user
                    $this->logout();
                }
            }
        }else{
            $this->find($user);//this will allow us to grab the data of the user that is notlogged in
        }
    }

    public function update($fields = [], $id = null){
        if(!$id && $this->isLoggedIn()){//this is to be able to update the user data without having to specify the id, for example updating the user details from the admin side
            $id = $this->data()->id;
        }
        if(!$this->_db->update('users', $id, $fields)){
            throw new Exception('There was a problem updating');
        }
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

    public function login($username = null, $password = null, $remember = false){//set the variables to null or false by default

        // print_r($this->_data);//for debugging purposes to see if the data is found
        // die();
        if(!$username && !$password && $this->exists()) {//this is to allow user that have not supplied username or password but their cookie is still active to login
            Session::put($this->_sessionName, $this->data()->id);//this is the  new User that was set in init line 35
        } else {
            $user = $this->find($username);

            if($user){
                if($this->data()->password === Hash::make($password, $this->data()->salt)){//this is to check the hashed password in the database with the password submitted in the form
                    // echo "Password is correct";
                    // die();
                    Session::put($this->_sessionName, $this->data()->id);  //this is to put the user id in the session
                    if($remember){
                        $hash = Hash::unique();//generate a unique hash
                        $hashCheck = $this->_db->get('users_session', ['user_id', '=', $this->data()->id]);//check if the hash already exists in the database, the user_id is the column name in the database where the user id is stored, the user_session table is the table name in the database where the user id and the hash are stored
                        // if($hashCheck->count()){
                        //     $this->_db->update('users_session', ['id', '=', $hashCheck->first()->id], [
                        //         'hash' => $hash
                        //     ]);//if the hash already exists in the database, then update the hash, the id is the column name in the database where the id is stored, the hash is the column name in the database where the hash is stored
                            if(!$hashCheck->count()){
                                $this->_db->insert('users_session', [
                                    'user_id' => $this->data()->id,
                                    'hash' => $hash
                                ]);
                            } else{
                        $hash = $hashCheck->first()->hash;
                        }
                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                    } 
                    return true;
                } 
            }
        }
        return false;
    }

    public function exists(){
        return (!empty($this->data)) ? true : false;
    }
    public function logout(){
        $this->_db->delete('users_session', ['user_id', '=', $this->data()->id]);
        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
        Session::delete('success');
    } 
    public function data(){
        return $this->_data;
    }
    public function isLoggedIn(){
        return $this->_isLoggedIn;
    }
}