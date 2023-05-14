<?php
require("./database.php");
require("../php/methods.php");

echo '
 <!DOCTYPE html>
    <html lang="hu">
    <head>
        <meta charset="UTF-8">
        <title>Regisztráció</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Rubik:400,700"> 
        <link rel="stylesheet" href="../stylesheet/loginpage_style.css">
        <script src="../scripts/script.js"></script> 
    </head>
    <body>
    <div class="login-form">
        <form method="post" action="">
            <h1 style="text-align: center">Registration</h1>
            <div class="content">            
                <div class="input-field">  
                    <input type="email" placeholder="Email address*" name="email" id="email" required>
                    <input type="password" placeholder="Password*" name="password" id="password" required>
                    <input type="password" placeholder="Password again*" name="password2" id="password2" required>
                    <input type="text" placeholder="First name*" name="firstname" id="firstname" required>                      
                    <input type="text" placeholder="Last name*" name="lastname" id="lastname" required>                      
                    <input type="date" max="9999-12-31" placeholder="Date of Birth*" name="date_of_birth" id="date_of_birth" required>
                </div>
                <div class="btn-group-vertical" role="group">
                    <input type="checkbox" class="btn-check" name="privacy_policy" id="privacy_policy" value="1"> <a href="https://loremipsum.io/privacy-policy/">Privacy Policy</a></input>                      
                    <input type="checkbox" class="btn-check" name="terms_of_service" id="terms_of_service" value="1"> <a href="https://www.termsfeed.com/live/df1bcb60-c34a-482f-9413-90fb11078960">Terms Of Service</a></input>   
                    
                </div>
                <a href="../index.php" class="link">Already have an account? Click here</a>    
            </div>            
            <div class="action">                
                <button type="submit" name="registration">Registration</button>                                                
            </div>
        </form>
    </div>           
    ';


try 
{
    $privacyPolicy = isset($_POST['privacy_policy'][0]) ? 1 : 0;
    $termsOfService = isset($_POST['terms_of_service'][0]) ? 1 : 0;
        
    if (isset($_POST["registration"]) && $_POST["password"] === $_POST["password2"] && $privacyPolicy == 1 && $termsOfService == 1) // If the two given password maches and the button gets pushed, the registration will be successful
    {
        $options = ['cost' => 12,];
        $password = password_hash($_POST["password"], PASSWORD_BCRYPT, $options); //BCRYPT algoryhtm to hash the input password, with 12 cost
               
        
        if(DB::query('SELECT emailaddress FROM users WHERE NOT EXISTS (SELECT emailaddress FROM users WHERE emailaddress = :emailaddress)', array(":emailaddress" => $_POST["email"]))) //You can't register with the same email again
        {
            DB::query('INSERT INTO users (emailaddress, password, firstname, lastname, date_of_birth, privacy_policy, terms_of_service) 
                                  VALUES(:emailaddress, :password, :firstname, :lastname, :date_of_birth, :privacy_policy, :terms_of_service)',
            array
                (
                    ":emailaddress" => $_POST["email"],
                    ":password" => $password,
                    ":firstname" => $_POST["firstname"],
                    ":lastname" => $_POST["lastname"],
                    ":date_of_birth" => $_POST["date_of_birth"],
                    ":privacy_policy" => $privacyPolicy,
                    ":terms_of_service" => $termsOfService
                )
            );

            echo '    
            <div class="alert alert-success text-center" id="alert">
                <span class="closebtn" onclick="closeWindow(\'alert\')">&times;</span> 
                <strong>Attention!</strong> The registration was successful.
            </div>';
                  
        }
        else if(DB::query('SELECT emailaddress FROM users WHERE EXISTS (SELECT emailaddress FROM users WHERE emailaddress = :emailaddress)', array(":emailaddress" => $_POST["email"]))) // if exists, then a message appears
        {
            echo '    
            <div class="alert text-center" id="alert">
                <span class="closebtn" onclick="closeWindow(\'alert\')">&times;</span> 
                <strong>Attention!</strong> The given email address is already registered.
            </div>';
        }       
       
    } 
    else if(isset($_POST["registration"]) && $_POST["password"] !== $_POST["password2"]) // On password mismatch, a message appears
    {
        echo '    
        <div class="alert text-center" id="alert">
            <span class="closebtn" onclick="closeWindow(\'alert\')">&times;</span> 
            <strong>Attention!</strong> The passwords do not match.
        </div>';
    }
    else if($termsOfService == 0 || $privacyPolicy == 0) 
    {
        echo '    
        <div class="alert text-center" id="alert">
            <span class="closebtn" onclick="closeWindow(\'alert\')">&times;</span> 
            <strong>Attention!</strong> You need to accept the Privacy Policy and the Terms of Service to register.
        </div>';
    }        
    else
    {
        echo '    
            <div class="alert text-center" id="alert">
                <span class="closebtn" onclick="closeWindow(\'alert\')">&times;</span> 
                <strong>Attention!</strong> All data marked with * are required for registration.
            </div>';
    }

  echo'</body></html>';


} 
catch (PDOException $e) 
{
    echo $e->getMessage();
}
