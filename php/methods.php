<?php
// This method checks which button is pressed on the header
function buttons()
{
    if (!empty($_POST)) {

        switch ($_POST) {
            case isset($_POST['logout']):
                session_unset(); // Unset all session variables
                session_destroy(); // Destroy the session    
                header("Location: ../index.php"); // Redirect to the login page
                exit(); // Make sure the script stops executing after the redirect    
                break;
            case isset($_POST['main_page']):
                header("Location: ./webshop.php");
                break;
            case isset($_POST['cart']):
                header("Location: ./cart.php");
                break;
            case isset($_POST['profile']):
                header("Location: ./profile.php");
                break;
            case isset($_POST['admin']):
                header("Location: ./admin.php");
                break;
        }
    }
}

function overallPrice()
{
    $sum = 0;
    for ($i = 0; $i < count($_SESSION["cart"]); $i++) {
        $sum += $_SESSION["cart"][$i]["quantity"] * $_SESSION["cart"][$i]["price"];
    }
    return $sum;
}

//Sorts the SESSION["cart"] array
function sessionSort()
{
    if(isset($_SESSION["cart"])) {

        // Get the session array
        $session_array = $_SESSION["cart"];
    
        // Define a new array to hold the sorted items
        $sorted_array = array();
    
        // Loop through each item in the session array
        foreach ($session_array as $item) {
    
            // Check if the item name already exists in the sorted array
            $index = array_search($item["name"], array_column($sorted_array, "name"));
    
            // If it does exist, increase the quantity
            if ($index !== false) {
                $sorted_array[$index]["quantity"] += $item["quantity"];
            }        
            // If it doesn"t exist, add the item to the sorted array
            else if($item["quantity"] > 0) {
                $sorted_array[] = $item;
                
            }
            
        }
    
        // Sort the sorted array based on the name
        usort($sorted_array, function ($a, $b) {
            return strcmp($a["name"], $b["name"]);
        });
    
        // Update the session array with the sorted array
        if(count($sorted_array) > 0)
        {
            $_SESSION["cart"] = $sorted_array;
        } else {
            unset($_SESSION["cart"]);
        }
    }
}

//Counts how many items are in the cart
function numberOfItems(){
    $pieces = 0;
    if(isset($_SESSION["cart"])){
        for($i = 0; $i < count($_SESSION["cart"]); $i++){
            $pieces += $_SESSION["cart"][$i]["quantity"];
        }
        return $pieces;
    }
    return 0;
}

function echoHeader($title){
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <title>'. $title .'</title>
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
        </header> 
        <br>       
        <br>       
        <br>       
        
        <div class="">            
            <div class="container">
                <div class="d-flex justify-content-center">
                    <div class="col-xs-12">
                        <h2 class="maintitle">'. $title .'</h2>                            
                    </div>
                </div>
            </div>        
        </div>  
   ';
}

function generateInvoiceItems(){
    for($i = 0; $i < count($_SESSION["cart"]); $i++)
    {
        echo'
        <tr>
            <td>('. $_SESSION["cart"][$i]["quantity"] .'x) ' . $_SESSION["cart"][$i]["name"] . '</td>
            <td class="alignright">'. $_SESSION["cart"][$i]["price"] * $_SESSION["cart"][$i]["quantity"] .'</td>
        </tr>';
        
    }
}
function showAdminButton()
{
    $isAdmin = DB::query('SELECT adminlevel FROM users WHERE id = :id', array(":id" => $_SESSION["userId"][0]["id"]));

    if($isAdmin[0]["adminlevel"] == 1)
    {
        return '<button type="submit" name="admin" class="btn btn-success">Admin</button>';
    }

}

function userDataIsFilled()
{
    if (isset($_SESSION["userData"]))
    {
        foreach($_SESSION["userData"] as $key)
        {
            if(is_null($key["telephone_number"]) || is_null($key["postalcode"]) || is_null($key["street"]) || is_null($key["city"]) || is_null($key["street_number"]))
            {
                return false;
                break;
            }
            else
            {
                return true;
            }
        }    
    }
    else
    {
        //All the current data of the logged in user will be shown in the input fields as the HTML tags' placeholder attribute
        $_SESSION["userData"] = DB::query(
        'SELECT emailaddress, firstname, lastname, telephone_number, postalcode, street, date_of_birth, postalcode, city, street_number, other_information 
         FROM users 
         WHERE id = :id', array(":id" => $_SESSION["userId"][0]["id"]));
         userDataIsFilled();
    }
}