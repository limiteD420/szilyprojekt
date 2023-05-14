<?php
require("./php/database.php");
session_start();

echo '
    <!DOCTYPE html>
    <html lang="hu">
    <head>
        <meta charset="UTF-8">
        <title>Bejelentkezés</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Rubik:400,700"> 
        <link rel="stylesheet" href="./stylesheet/loginpage_style.css">
        <script src="./scripts/script.js"></script> 
    </head>
    <body>
    <div class="login-form">
        <form method="post" action="index.php">
            <h1 style="text-align: center">Login</h1>
            <div class="content">
                <div class="input-field">
                    <input type="email" placeholder="Email" name="email" id="email">
                </div>
                <div class="input-field">
                    <input type="password" placeholder="Password" name="password" id="password">
                </div>
                <!--<a href="#" class="link">Elfelejtetted a jelszavad?</a>-->
                <a href="./php/registration.php" class="link">Don\'t have an account yet? Click here to register.</a>
            </div>
            <div class="action">                
                <button type="submit" name="login">Login</button>                                 
            </div>
        </form>
    </div>
           
    </body>
    </html>';

    
try {
    // When login button is pressed and both fields are filled, checks that the given email exists and the hashed password matches with the one stored in the DB
    if (isset($_POST["login"]) && (!empty(trim($_POST["email"] ?? null)) && !empty(trim($_POST["password"] ?? null))))   
    {
        $email = trim($_POST["email"]);
        $options = ['cost' => 12,];
        //$password = password_hash(trim($_POST["password"]), PASSWORD_BCRYPT, $options);

        $hashedPassword = DB::query('SELECT password FROM users WHERE emailaddress = :emailaddress', array(":emailaddress" => $email));
        $emailExists = DB::query('SELECT emailaddress FROM users WHERE EXISTS (SELECT emailaddress FROM users WHERE emailaddress = :emailaddress)', array(":emailaddress" => $email));
        
        //If the login credentials are correct, the user will be redirected to the main page
        if ($emailExists && password_verify($_POST["password"], $hashedPassword[0]["password"])) {
            $_SESSION["loggedIn"] = "Success";
            $_SESSION["userId"] = DB::query('SELECT id FROM users WHERE emailaddress = :emailaddress', array(":emailaddress" => $email));
            header("Location: ./php/webshop.php");
        } //If not, an error message appears
        else {
            echo '    
            <div class="alert text-center" id="alert">
                <span class="closebtn" onclick="closeWindow(\'alert\')">&times;</span> 
                <strong>Attention!</strong> Incorrect email or password.
            </div>';
        }
        
    } //If any of the input fields are empty, an error message appears
    else if (isset($_POST["login"]) && (empty(trim($_POST["email"] ?? null)) || empty(trim($_POST["password"] ?? null)))) {
        echo '    
            <div class="alert text-center" id="alert">
                <span class="closebtn" onclick="closeWindow(\'alert\')">&times;</span> 
                <strong>Attention!</strong> All details are required to log in.
            </div>';            
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
