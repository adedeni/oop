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
   
    $user = DB::getInstance()->get('users', ['username', '=', 'ADEDENI']);// THIS CONNECT TO DATABASE, and make it easier to write the query since we have binded the parameters in the DB class and passed values already, a get method is used to get data from the database, the delete method is used to delete data from the database, the update method is used to update data in the database, the insert method is used to insert data into the database
    //var_dump($user->error());
    // if($user->error()){
        
    //     echo "There was an error";
    // } else {
    //     echo "There was no error";
    // } this is if there error in our query
    if(!$user->count()){
        echo "There are no users";
    } else {
        echo "There are " . $user->count() . " users";
    }
    

    ?>
</body>
</html>