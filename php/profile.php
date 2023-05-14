<?php
require("../php/database.php");
require("../php/methods.php");
session_start();

if (isset($_SESSION["loggedIn"]) && isset($_SESSION["userId"])) {

    buttons();
    if(isset($_POST["saveChanges"])){
        header("Location: profile.php");
    }
    //Iterating through $_POST, if any of the fields have an iput, the right column in the DB will be modified after pushing the Save button
    foreach ($_POST as $key => $value) {
        switch ($key) {
            case 'firstname':
                if (!empty($value)) {
                    DB::query('UPDATE users SET firstname ="' . $value .'" WHERE id = :id', array(":id" => $_SESSION["userId"][0]["id"]));
                }
                break;
            case 'lastname':
                if (!empty($value)) {
                    DB::query('UPDATE users SET lastname ="' . $value .'" WHERE id = :id', array(":id" => $_SESSION["userId"][0]["id"]));
                }
                break;
            case 'telephone_number':
                if (!empty($value)) {
                    DB::query('UPDATE users SET telephone_number ="' . $value .'" WHERE id = :id', array(":id" => $_SESSION["userId"][0]["id"]));
                }
                break;
            case 'postalcode':
                if (!empty($value)) {
                    DB::query('UPDATE users SET postalcode ="' . $value .'" WHERE id = :id', array(":id" => $_SESSION["userId"][0]["id"]));
                }
                break;
            case 'city':
                if (!empty($value)) {
                    DB::query('UPDATE users SET city ="' . $value .'" WHERE id = :id', array(":id" => $_SESSION["userId"][0]["id"]));
                }
                break;
            case 'street':
                if (!empty($value)) {
                    DB::query('UPDATE users SET street ="' . $value .'" WHERE id = :id', array(":id" => $_SESSION["userId"][0]["id"]));
                }
                break;
            case 'street_number':
                if (!empty($value)) {
                    DB::query('UPDATE users SET street_number ="' . $value .'" WHERE id = :id', array(":id" => $_SESSION["userId"][0]["id"]));
                }
                break;
            case 'other_information':
                if (!empty($value)) {
                    DB::query('UPDATE users SET other_information ="' . $value .'" WHERE id = :id', array(":id" => $_SESSION["userId"][0]["id"]));
                }
                break;            
            default:
                break;
        }
    }
    //All the current data of the logged in user will be shown in the input fields as the HTML tags' placeholder attribute
    $_SESSION["userData"] = DB::query(
    'SELECT emailaddress, firstname, lastname, telephone_number, postalcode, street, date_of_birth, postalcode, city, street_number, other_information 
     FROM users 
     WHERE id = :id', array(":id" => $_SESSION["userId"][0]["id"]));

              
     echo '
     <!DOCTYPE html>
     <html lang="hu">
 
     <head>
         <meta charset="UTF-8">
         <meta http-equiv="X-UA-Compatible" content="IE=edge">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <link href="../stylesheet/bootstrap/lib/bootstrap-5.1.0-dist/css/bootstrap.min.css" rel="stylesheet">
         <link rel="stylesheet" href="../stylesheet/stylesheet.css">             
         <script src="../scripts/script.js"></script>         
         <title>Profile</title>
     </head>
 
     <body>
 
         <header class="container-fluid">
             <div class="row">
                 <div classs="container">
                     <div class="row">
                         <h1 class="col-sm-4 headertitle"><a href="./webshop.php" style="height: 0px;width: 0px;overflow:hidden; text-decoration: none;">J-A-M Webshop</a></h1>
                         <nav class="col-sm-8">
                         <form method="post">
                             <button type="submit" name="main_page" class="btn btn-secondary" id="MainPage">Main Page</button>
                             <button type="submit" name="cart" class="btn btn-secondary">Cart ('.numberOfItems().')</button>
                             <button type="submit" name="profile" class="btn btn-secondary">Profile</button>
                             '.  showAdminButton() .'
                             <button type="submit" name="logout" class="btn btn-danger">Log Out</button>
                             
                         </form>    
                         </nav>
                     </div>
                 </div>
             </div>
         </header>';
  
    echo '                
        <div class="container bg-white py-2">
        <div class="row align-items-center justify-content-center">        
        <div class="col-md-5">
            <div>                
                <div class="text-center align-items-center justify-content-center d-flex">
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                        <h2 class="maintitle">Profile Settings</h2>
                        <img class="rounded-circle mt-1" width="150px" src="..\imgs\profile_picture.webp">
                        <span class="font-weight-bold">' . $_SESSION["userData"][0]["firstname"] ." ". $_SESSION["userData"][0]["lastname"] . '</span>
                        <span class="text-black-50"> ' . $_SESSION["userData"][0]["emailaddress"] . '</span>
                        <span class="text-black-50"> ' . $_SESSION["userData"][0]["date_of_birth"] . '</span>
                    </div>    
                </div>
                <form method="post" action="profile.php">                 
                <div class="row mt-2">
                    <div class="col-md-6"><label class="labels">First Name</label><input type="text" class="form-control" name="firstname" placeholder=" ' . $_SESSION["userData"][0]["firstname"] . '" value=""></div>
                    <div class="col-md-6"><label class="labels">Last Name</label><input type="text" class="form-control" name="lastname" value="" placeholder="' . $_SESSION["userData"][0]["lastname"] . '"></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Telephone Number</label><input type="text" class="form-control" name="telephone_number" placeholder="' . $_SESSION["userData"][0]["telephone_number"] . '" value=""></div>
                    <div class="col-md-12"><label class="labels">Postal Code</label><input type="text" class="form-control" name="postalcode" placeholder="' . $_SESSION["userData"][0]["postalcode"] . '" value=""></div>
                    <div class="col-md-12"><label class="labels">City</label><input type="text" class="form-control" name="city" placeholder="' . $_SESSION["userData"][0]["city"] . '" value=""></div>
                    <div class="col-md-12"><label class="labels">Street</label><input type="text" class="form-control" name="street" placeholder="' . $_SESSION["userData"][0]["street"] . '" value=""></div>
                    <div class="col-md-12"><label class="labels">Street number</label><input type="text" class="form-control" name="street_number" placeholder="' . $_SESSION["userData"][0]["street_number"] . '" value=""></div>
                    <div class="col-md-12"><label class="labels">Other information</label><input type="text" class="form-control" name="other_information" placeholder="' . $_SESSION["userData"][0]["other_information"] . '" value=""></div>                    
                    <div class="text-center"><button class="btn btn-primary profile-button mt-2" type="submit" name="saveChanges">Save Changes</button></div>
                </div>                
                </form>
            </div>
        </div>        
        </div>
        </div>
        </div>
        </div>       

    </body>
    </html>';
        
} else {
    header("Location: ../index.php");
}
