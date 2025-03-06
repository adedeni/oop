<?php
class Session{
    public static function exists($name){//this is to check if the session exists
        return (isset($_SESSION[$name])) ? true : false;
    }

    public static function put($name, $value){//this is to put a value in the session
        return $_SESSION[$name] = $value;
    }

    public static function get($name){//this is to get a value from the session
        return $_SESSION[$name];
    }

    public static function delete($name){//this is to delete a value from the session
        if(self::exists($name)){
            unset($_SESSION[$name]);
        }
    } 
    //flashing data to the session is a way to display a message to the user after a form is submitted and then the message is deleted from the session after the user sees the message
    public static function flash($name, $string = ''){
        if(self::exists($name)){
            $session = self::get($name);
            self::delete($name);
            return $session;
        }else{
            self::put($name, $string);
        }
    }
}

