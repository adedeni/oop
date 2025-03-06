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
}
