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
        if(!$validation->passed()){
            //print_r($validation->errors()); this is to print the errors in the array format
            foreach($validation->errors() as $error){
                echo $error, '<br>';
            }
        }else{
            Session::flash('success', 'You have registered successfully');
            header('Location: index.php');
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