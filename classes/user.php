<?php
class User{
    private $_db,
    $_data,
    $_sessionName,
    $_cookieName;

    public function __construct($user = null){
        $this->_db = DB::getInstance();
    }
    public function create($fields = []) {
        try {
            // Debug: Print SQL query before execution
            echo "Attempting database insertion...<br>";
            
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
}
