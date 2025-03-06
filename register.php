<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
</head>
<body>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
    require_once 'core/init.php';
    //var_dump(Token::check(Input::get('token')));//this is see if the token check is working
    if(Input::exists()){
        //echo "Input exists";
        //echo Input::get('username');//this is to show that the data is getting from the post or get
        if(Token::check(Input::get('token'))){
            //the token class is used to prevent cross-site request forgery attacks
            //echo "Token is valid" . '<br>';
            $validate = new Validate();
       $validation = $validate->check($_POST, [
        'name' => [
            'required' => true,
            'min' => 2,
            'max' => 50
        ],
        'username' => [
            'required' => true,//input field is required
            'min' => 2,//characters value
            'max' => 20,
            'unique' => 'users'//this is to make sure that the username is unique in the users table
        ],
        'password' => [
            'required' => true,
            'min' => 6,
        ],
        'confirm_password' => [ 
            'required' => true,
            'matches' => 'password'
        ]
        ]);
        if($validation->passed()){
            $user = new User();
            $salt = Hash::salt(32);//32 here is the lenght of salt we plan to use as set in our database
            try{
                //echo "Attempting to create user with data:<br>";//for debugging purposes
                $userData = [
                    'name' => Input::get('name'),
                    'username' => Input::get('username'),
                    'password' => Hash::make(Input::get('password'), $salt),
                    'salt' => $salt,
                    'joined' => date('Y-m-d H:i:s'),
                    'group' => 1
                ];
                //var_dump($userData);//for debugging purposes
                
                //echo "<br>About to attempt user creation...<br>";//for debugging purposes
                
                $created = $user->create($userData);
                
                // if($created) {
                    Session::flash('success', 'You have registered successfully');
                    //Redirect::to('404');//this is to redirect to the error 404 page
                    Redirect::to('index.php');
                // } else {
                //     //echo "<br>Creation failed. Database Error: " . $user->getLastError() . "<br>";// for debugging purposes
                // }
                
            } catch(Exception $e) {
                echo "Registration failed: " . $e->getMessage() . "<br>";//for debugging purposes
                //echo "Error details: " . $e->getTraceAsString() . "<br>";//for debugging purposes
            }
        }else{
            //print_r($validation->errors()); this is to print the errors in the array format
            foreach($validation->errors() as $error){
                echo $error, '<br>';
            }
        }
       
    }
}
    ?>
    <form action="" method="post">
    <div class="field">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>" autocomplete="off"><!--the input::get('name') is to recall the name that was prefilled back in case of an error or the page refresh, the escape is to prevent any malicious code from being injected into the database, to sanitize the data-->
        </div> <br>
        <div class="field">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off">
        </div> <br>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" value="" autocomplete="off">
        </div> <br>
        <div class="field">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" value="" autocomplete="off">
        </div> <br>
        <input type="hidden" name="token" value="<?php echo Token::generate(); //this is to generate a token for the form?>">
        <input type="submit" value="Register">
    </form>
</body>
</html>