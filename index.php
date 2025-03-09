<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body> 
    <h1>This is index</h1>   
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
    // $db = DB::getInstance();
    // $user = $db->insert('users', [
    //     'username' => 'adedeni',
    //     'password' => 'password',
    //     'salt' => 'salt',
    //     'name' => 'Adeshina Olayode',//i can not leave this because it has no default value and set to not null in the database, including joined and group column
    //     'joined' => date('Y-m-d H:i:s'),
    //     'group' => 1
    // ]);

    // if($user) {
    //     echo "Data inserted successfully";
    // } else {
    //     echo "Data not inserted.<br>";
    //     echo "SQL Error Message: " . $db->getError() . "<br>";
    // }
    // $db = DB::getInstance();
    // $user = $db->update('users', 5, [
    //     'username' => 'opasix',
    //     'password' => 'updatedPassword',
    //     'name' => 'Adeshina Saheed'
    // ]);

    // if($user) {
    //     echo "Data updated successfully";
    // } else {
    //     echo "Data not updated.<br>";
    //     echo "SQL Error Message: " . $db->getError() . "<br>";
    // }
        if(Session::exists('success')){//this is to show flash the success message after the user registers successfully
            echo Session::flash('success');
        }
        //echo Session::get(Config::get('session/session_name'));//this is to show the id of the user in the session
        //die();
        $user = new User();//logged in user
        //echo $user->data()->username;this is to show the username of the logged in user
        if($user->isLoggedIn()){
            ?>
            <p>Welcome, <?php echo $user->data()->username; ?>!</p>
            <ul>
                <li><a href="logout.php">Logout</a></li>
            </ul>
            <?php
        }else{
            echo "You need to <a href='login.php'>Login</a> or <a href='register.php'>Register</a>";
        } 
    ?>
</body>
</html>