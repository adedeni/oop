 <?php
 session_start();

 $GLOBALS['config'] = [
    'mysql' => [
        'host' => '127.0.0.1', //localhost address
        'username' =>'root',
        'password' => '',
        'db' => 'oop' //database name
    ],
    'remember' => [
        'cookie_name' => 'hash', //you can name it anything
        'cookie_expiry' => 604800 //cookie lapse for a week 
    ],
    'session' => [
        'session_name' => 'user', 
        'token_name' => 'token'
    ]
 ];

 //THIS IS AUTOLOAD FUNCTION
spl_autoload_register(function($class){
    require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';