<?php
require("../php/database.php");
require("../php/methods.php");
session_start();

if(!isset($_SESSION["userData"]))
{
    //All the current data of the logged in user will be shown in the input fields as the HTML tags' placeholder attribute
    $_SESSION["userData"] = DB::query(
        'SELECT emailaddress, firstname, lastname, telephone_number, postalcode, street, date_of_birth, postalcode, city, street_number, other_information 
         FROM users 
         WHERE id = :id', array(":id" => $_SESSION["userId"][0]["id"]));
}

if (isset($_SESSION["loggedIn"]))
{
    buttons();   

    echo '
    <!DOCTYPE html>
    <html lang="hu">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../stylesheet/bootstrap/lib/bootstrap-5.1.0-dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../stylesheet/stylesheet.css">
        <link rel="stylesheet" href="../stylesheet/payment.css">
        <script src="../scripts/script.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <title>Payment</title>
    </head>

    <body>
        <header class="container-fluid">
            <div class="row">
                <div classs="container">
                    <div class="row">
                        <h1 class="col-sm-4">Logo</h1>
                        <nav class="col-sm-8">
                        <form method="post">
                            <button type="submit" name="main_page" class="btn btn-secondary" id="MainPage">Main Page</button>
                            <button type="submit" name="cart" class="btn btn-secondary">Cart('.numberOfItems().')</button>
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
        <div class="container-fluid">
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <p class="maintitle">Payment</p>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-flex flex-column h-100">';

    if(!isset($_POST["paymentButton"]))
    {
        echo'
        <div class="container p-0">
        <div class="card px-4">
            <p class="h8 py-3">Payment Details</p>
            <div class="row gx-3">
                <div class="col-12">
                    <div class="d-flex flex-column">
                        <p class="text mb-1">Cardholder\'s name</p>
                        <input class="form-control mb-3" type="text" placeholder="Name" value="John Doe">
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex flex-column">
                        <p class="text mb-1">Card Number</p>
                        <input class="form-control mb-3" type="text" placeholder="5435 6782 4567 5463">
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column">
                        <p class="text mb-1">Expiration date</p>
                        <input class="form-control mb-3" type="text" placeholder="MM/YYYY">
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column">
                        <p class="text mb-1">CVV/CVC</p>
                        <input class="form-control mb-3 pt-2 " type="password" placeholder="***">
                    </div>
                </div>
                <form method="post" action="./payment.php">                            
                        <button type="submit" name="paymentButton" class="btn btn-primary mb-3">Pay '. overallPrice() .' HUF</button>
                </form>
            </div>
        </div>
        </div>';

    }
    else
    {    
        echo'     
        <div class="d-flex justify-content-center text-center">
            <table class="body-wrap">
            <tbody><tr>
            <td></td>
            <td class="container" width="600">
                <div class="content">
                    <table class="main" width="100%" cellpadding="0" cellspacing="0">
                        <tbody><tr>
                            <td class="content-wrap aligncenter">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tbody><tr>
                                        <td class="content-block">
                                            <h2>Thanks for purchasing</h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            <table class="invoice">
                                                <tbody><tr>
                                                    <td>' . $_SESSION["userData"][0]["firstname"] ." ". $_SESSION["userData"][0]["lastname"] . '<br>Invoice<br></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table class="invoice-items" cellpadding="0" cellspacing="0">
                                                            <tbody>';
                                                                generateInvoiceItems();                                                                                                           
                                                            echo'                                                        
                                                            <t  r class="total">
                                                                <td class="alignright" width="80%">Total</td>
                                                                <td class="alignright">'. overallPrice() .' HUF</td>
                                                            </tr>
                                                        </tbody></table>
                                                    </td>
                                                </tr>
                                            </tbody></table>
                                        </td>
                                    </tr>                               
                                </tbody></table>
                            </td>
                        </tr>
                    </tbody></table>
                    </div>
            </td>
            <td></td>
            </tr>
            </tbody></table>
        </div>';       
        //After Payment, substracting the ordered quantities from the DB
        foreach($_SESSION["checkOut"] as $key)
        {            
            DB::query('UPDATE products SET product_quantity = product_quantity - "' . $key["quantity"] .'" WHERE product_name = :product_name', array(":product_name" => $key["name"]));
        }
        $product_data = $_SESSION['checkOut'];
        DB::insertOrder($product_data, overallPrice());
        unset($_SESSION["cart"]);
    }  
            
    echo'</div></body></html>';    
 
}
else 
{
    header("Location: ../index.php");
}