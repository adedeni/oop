<?php
class Token{
    public static function generate(){
        return Session::put(Config::get('session/token_name'), md5(uniqid()));//the config is to get the token name from the config file, and this is to generate a new token for the form using the session class
    }

    public static function check($token){//this is to check if the token is valid
        $tokenName = Config::get('session/token_name');
        if(Session::exists($tokenName) && $token === Session::get($tokenName)){//this is to check if the token exists in the session and if it is the same as the token in the form
            Session::delete($tokenName);//this is to delete the token from the session after it has been used
            return true;
        }
        return false;
    }

}
