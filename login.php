<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once 'core/init.php';
    if(Input::exists()){
        //echo "Input exists";//this is to check if the form submitted some inputs
        if(Token::check(Input::get('token'))) {//this is to check if the token is valid
            $validate = new Validate();
            $validation = $validate->check($_POST, [
                'username' => ['required' => true],
                'password' => ['required' => true]//you can add more validation rules here, like min, max, unique, etc.
            ]);
            if($validation->passed()){
                //echo "Validation passed";//for debugging purposes to see if the validation passed
                $user = new User();//make a new user object
                $login = $user->login(Input::get('username'), Input::get('password'));//pass the username and password to the login method
                if($login){
                    //echo "Login successful";//for debugging purposes to see if the login was successful
                    Session::flash('success', 'You have been logged in');
                    Redirect::to('index.php');
                    
                }else{
                    echo "Login failed";
                }
            }else{
                foreach($validation->errors() as $error){
                    echo $error, '<br>';
                }
            }
        }
    }
    if(isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $token = $_POST['token'];
    }
    ?>
    <form action="" method="post">
       <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" placeholder="Enter your username" required autocomplete="off" value="<?php echo escape(Input::get('username')); ?>">
       </div> <br>
       <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required autocomplete="off">
       </div>
       <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
       <input type="submit" name="login" value="Log in">
    </form> <br>
    <a href="register.php">Register</a>
</body>
</html>