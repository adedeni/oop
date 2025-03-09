<?php
class Cookie{
    public static function exists($name){//this method is used to check if a cookie exists or not
        return (isset($_COOKIE[$name])) ? true : false;
    }
    public static function get($name){//this method is used to get the value of a cookie
        return $_COOKIE[$name];
    }
    public static function put($name, $value, $expiry){//this method is used to put a cookie
        if (is_int($expiry) && setcookie($name, $value, time() + $expiry, '/')) {//we have to append the current time to the expiry time to make the cookie expire after the expiry time and also the / is to make the cookie available to all the subdomains of the domain like a path
            return true;
        }
        return false;
    }
    public static function delete($name){//this method is used to delete a cookie, you have to have the put method to delete a cookie becaause you don't really unset a cookie you reset it to a null value or empty string
        self::put($name, '', time() - 1);
    }
}