<?php
class Hash{
    //what salt does is to make the password hash more secure by adding a random string to the password for example if in a database i have a password 'password' and i hash it using sha256 the hash will be the same for all users with the same password, but if i add a salt to the password the hash will be different for all users with the same password
    public static function make($string, $salt = ''){//this is the method that makes the password hash
        return hash('sha256', $string . $salt);//there are many hashing algorithms in php, sha256 is one of them, other algorithms are md5, sha1, sha256, sha512, etc.
    }
    public static function salt($length){//this is the method that generates a random salt
        return md5($length);//use md5 because it is faster than sha256, do not use mcrypt_create_iv() because it is deprecated after php v7.2, md5 is a function that generates a hash of the string, do not use bin2hex() because it  will keep changing the salt
    }
    public static function unique(){//this is the method that generates a unique hash
        return self::make(uniqid());
    }
}
