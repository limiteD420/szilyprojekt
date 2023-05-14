<?php
require("../php/database.php");
require("../php/methods.php");
session_start();
// Some pages are only visible if the login was successful, if not then it redirects to the login page
if (isset($_SESSION["loggedIn"])) {
    
    // This method checks which button is pressed on the header
    buttons();    
    
    if(userDataIsFilled() === true && isset($_POST["gomb"])) {
        $product_id = $_POST["data-id"];
        $product_name = $_POST["data-name"];
        $product_price = $_POST["data-price"];
        $product_quantity = $_POST["quantity"];
        $product_image = $_POST["data-image"];
        $product_category = $_POST["data-category"];
    
        // Add the product data to the session variable
        $_SESSION["cart"][] = [
            "id" => $product_id,
            "name" => $product_name,
            "price" => $product_price,
            "quantity" => $product_quantity,
            "image" => $product_image,
            "category" => $product_category
        ];
        sessionSort();
    }
    else if(userDataIsFilled() === false)
    {
        echo '    
            <div class="alert text-center fixed-bottom" id="alert">
                <span class="closebtn" onclick="closeWindow(\'alert\')">&times;</span> 
                <strong>Attention!</strong> Fill all the Personal Data at the Profile section to put products into the cart.
            </div>';
    }
    echoHeader("Available Products");
    

    // A query for all products in stock 
    $termekek = DB::query('SELECT * FROM products WHERE product_quantity > :product_quantity', array("product_quantity" => 0));

    // Displays the query result  
    //echo ' <div class="container col-md-5"><div class="d-flex justify-content-center">';
    /*for ($i = 0; $i < count($termekek); $i++) {
        echo '
        <div class="col-sm-6 col-md-3 col-sm-pull-6 col-md-pull-3">
        <div class="box card">
            <img src="' . $termekek[$i]["product_image"] . '" alt="' . $termekek[$i]["product_name"] . '" class="img-thumbnail">
            <div class="card-body">
                <p class="card-text">' . $termekek[$i]["product_name"] . '</p>
                <p class="card-text">Category: ' . $termekek[$i]["product_category"] . '</p>
                <p class="card-text">Left in Stock: ' . $termekek[$i]["product_quantity"] . ' pieces</p>        
                <p class="card-text">Price: ' . $termekek[$i]["product_price"] . ' HUF</p>        
            </div>
        
            <form method="post" action="./webshop.php">
            <div class="d-flex justify-content-center flex-column">
                <input type="hidden" name="data-id" value="' . $termekek[$i]["id"] . '">
                <input type="hidden" name="data-name" value="' . $termekek[$i]["product_name"] . '">
                <input type="hidden" name="data-price" value="' . $termekek[$i]["product_price"] . '">
                <input type="hidden" name="data-category" value="' . $termekek[$i]["product_category"] . '">
                <input type="hidden" name="data-image" value="' . $termekek[$i]["product_image"] . '">               
                <div class="">
                    <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="' . $termekek[$i]["product_quantity"] . '">
                </div>
                <div class="d-flex justify-content-center py-2">
                <button type="submit" class="btn btn-danger btn-block" name="gomb">Add to Cart</button>
                </div>
            </div>    
            </form>
        
        </div>
        </div>';       
            
            
    }*/
    // Every button of the listed products are uniqe. The button contains all the data (hidden inputs), so always the right product goes into the cart. 
    echo ' <div class="container"><div class="d-flex justify-content-center">';
            for ($i = 0; $i < count($termekek); $i++) {
                if ($i % 4 == 0 && $i != 0) {
                    echo '</div><div class="d-flex justify-content-center">';
                }
                echo '
                <div class="col-sm-6 col-md-3 col-sm-pull-6 col-md-pull-3">
                    <div class="box card">
                        <img src="' . $termekek[$i]["product_image"] . '" alt="' . $termekek[$i]["product_name"] . '" class="img-thumbnail">
                        <div class="card-body">
                            <p class="card-text">' . $termekek[$i]["product_name"] . '</p>
                            <p class="card-text">Category: ' . $termekek[$i]["product_category"] . '</p>
                            <p class="card-text">Left in Stock: ' . $termekek[$i]["product_quantity"] . ' pieces</p>        
                            <p class="card-text">Price: ' . $termekek[$i]["product_price"] . ' HUF</p>        
                        </div>
                    
                        <form method="post" action="./webshop.php">
                        <div class="d-flex justify-content-center flex-column">
                            <input type="hidden" name="data-id" value="' . $termekek[$i]["id"] . '">
                            <input type="hidden" name="data-name" value="' . $termekek[$i]["product_name"] . '">
                            <input type="hidden" name="data-price" value="' . $termekek[$i]["product_price"] . '">
                            <input type="hidden" name="data-category" value="' . $termekek[$i]["product_category"] . '">
                            <input type="hidden" name="data-image" value="' . $termekek[$i]["product_image"] . '">               
                            <div class="">
                                <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="' . $termekek[$i]["product_quantity"] . '">
                            </div>
                            <div class="d-flex justify-content-center py-2">
                            <button type="submit" class="btn btn-danger btn-block" name="gomb">Add to Cart</button>
                            </div>
                        </div>    
                        </form>
                    </div>
                </div>';
            }
            echo '</div></div></body></html>';
  
    
    
    
       
} else {
    header("Location: ../index.php");
}
