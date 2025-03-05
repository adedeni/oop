<?php

class Input {
    public static function exists($type = 'post') {//the default type is post you can also change it to get, this method is used to check if a data exist
      switch($type) {
        case 'post'://this case is to check if the data is post
            return (!empty($_POST)) ? true : false;
            break;
        case 'get'://this case is to check if the data is get
            return (!empty($_GET)) ? true : false;
            break;
        default:
            return false; //this is to return false if the data is not post or get
            break;
      }
    }

    public static function get($item) {//this method is used to get the data from the post or get
        if(isset($_POST[$item])) {
            return $_POST[$item];
        } else if(isset($_GET[$item])) {
            return $_GET[$item];
        }
        return '';
    } 
}