<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>    
    <?php
    require_once 'core/init.php'; 
    //var_dump(config::get('mysql/host'));//this is to check if the config is loaded successfully
   
    //$user = DB::getInstance()->get('users', ['username', '=', 'ADEDENI']);// THIS CONNECT TO DATABASE, and make it easier to write the query since we have binded the parameters in the DB class and passed values already, a get method is used to get data from the database, the delete method is used to delete data from the database, the update method is used to update data in the database, the insert method is used to insert data into the database
    //var_dump($user->error());
    // if($user->error()){
        
    //     echo "There was an error";
    // } else {
    //     echo "There was no error";
    // } this is if there error in our query
    // if(!$user->count()){
    //     echo "There are no users";
    // } else {
    //     echo "There are " . $user->count() . " users";
    // } this is to check the number of users in the database
    // $user = DB::getInstance()->query("SELECT * FROM users");//this is to query the database 
    // if(!$user->count()){
    //     echo "There are no users";
    // } else {
    //     foreach($user->results() as $user){
    //         echo $user->username . "<br>";
    //     }
    // }//this is to display all the users in the database
    // $user = DB::getInstance()->get('users', ['username', '>=', '0']);//this is to get the first result from the database
    // if(!$user->count()){
    //     echo "There are no users";
    // } else {
    //     echo $user->first()->username;
    // }
    ?>
</body>
</html>