<?php
require("../php/database.php");
require("../php/methods.php");

session_start();

    buttons();
    sessionSort();
    echoHeader("Cart");    
    
    if (isset($_SESSION["loggedIn"])) 
    {
        if(isset($_POST["ProceedToPay"]))
        {
            header("Location: ../php/payment.php");
        }
        else 
        {   
            foreach ($_POST as $key => $value) {
                if (str_starts_with($key, 'deleteItem-')) {
                    $step = explode('-', $key)[1];

                    unset($_SESSION['cart'][$step]);

                    $_SESSION['cart'] = array_values($_SESSION['cart']);

                    break;
                }
            } 
        } 
                           
    if (isset($_SESSION["cart"])) 
    {        
        // Initialize a new associative array to store products by name
        $productsByName = array();

        // Loop through the items in the cart
        foreach ($_SESSION["cart"] as $item) {
            $name = $item["name"];
            $quantity = $item["quantity"];
            $price = $item["price"];
            $id = $item["id"];

            // If this name hasn't been added yet, add it to the array
            if (!isset($productsByName[$name])) {
                $productsByName[$name] = array(
                    "name" => $item["name"],
                    "image" => $item["image"],
                    "quantity" => $quantity,
                    "price" => $item["price"],
                    "id" => $item["id"]
                );
            } else {
                // Otherwise, increment the quantity for this name
                $productsByName[$name]["quantity"] += $quantity;
            }
        }
        
        
        $_SESSION["checkOut"] = array();
        $i = 0;
        while (isset($_POST["nameOfProduct" . $i])) 
        {
           array_push($_SESSION["checkOut"], array(
                "name" => $_POST["nameOfProduct" . $i],
                "quantity" => $_POST["quantityOfProduct" . $i],
                "price" => $_POST["quantityOfProduct" . $i]
           ));
            //Increasing the quantity of the right item in the $productsByName
            foreach ($productsByName as &$key){                
                if ($key["name"] === $_POST["nameOfProduct" . $i])
                {
                    $key["quantity"] = $_SESSION["checkOut"][$i]["quantity"];
                    break;                                                          
                }
            }
            //Increasing the quantity of the right item in the $_SESSION["cart"]
            foreach ($_SESSION["cart"] as &$key){                
                if ($key["name"] === $_POST["nameOfProduct" . $i])
                {
                    $key["quantity"] = $_SESSION["checkOut"][$i]["quantity"];
                    break;                                                          
                }
            }            
            $i++;            
        }       
        
        echo '<form method="post" action="cart.php">';               
        
        // Loop through the productsByName array to display the products 
        $step = 0;
        foreach ($productsByName as $name => &$product) 
        {            
            $quantity_left = DB::query('SELECT product_quantity FROM products WHERE product_name = :product_name', array("product_name" => $product["name"]));
            if($product["quantity"] > 0) 
            {
                echo '
                    <div class="container h-100 py-1 ">
                        <div class="row d-flex justify-content-center align-items-center h-100">
                            <div class="col-sm-10 col-md-10 col-lg-10">   
                                <div class="card rounded-3 mb-4">
                                    <div class="card-body p-4">
                                        <div class="row d-flex justify-content-between align-items-center">
                                            <div class="col-md-2 col-lg-2 col-xl-2">
                                                <img src="' . $product["image"] . '" class="img-fluid rounded-3" alt="' . $product["name"] . '">
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-xl-3">
                                                <p class="lead fw-normal mb-2">' . $product["name"] . '</p>
                                                <p><span class="text-muted">Type: </span>' . $name . '</p>
                                            </div>
                                            <div class="col-md-4 col-lg-4 col-xl-4 d-flex flex-row">
                                                <div class="d-flex flex-row">                                     
                                                    <input id="form1" min="0" max="' . $quantity_left[0]["product_quantity"] . '" value="' . $product["quantity"] . '" name="quantityOfProduct' . $step . '" type="number" class="form-control form-control-sm col-md-2" onchange="updatePrice(this.closest(\'.card-body\')); updateOverallPrice();"/>
                                                    <input type="hidden" name="nameOfProduct'. $step . '" value="' . $product["name"] . '">
                                                    <input type="hidden" name="deleteItemByName' . $step .'" value="' . $product["name"] . '">
                                                    <button type="submit" name="deleteItem-' . $step . '" class="btn btn-danger">&times;</button>                                                
                                                </div>
                                            </div>                                
                                            <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                                <h5 class="mb-0">Price:</h5>
                                                <h5 class="mb-0" id="productPrice">' . $product["price"] * $product["quantity"] . ' HUF</h5>
                                                <input type="hidden" id="prodPrice" value="' . $product["price"] . '"> 
                                            </div>                                 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';                    
            }
            $step++;                    
        }
        if(isset($_SESSION["cart"]))
        {
            echo '
            <div class="container h-100 py-1">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-sm-10 col-md-10 col-lg-10">
                        <div class="card">
                            <div class="card-body row">                
                                <h3 class="col-md-10" id="overallPrice">Overall price: '. overallPrice() .'</h3>                                                
                            </div>                                   
                        </div>        
                    </div>
                    <div class="col-sm-10 col-md-10 col-lg-10 text-center py-2">        
                        <button type="submit" name="ProceedToPay" class="btn btn-warning btn-block btn-lg">Proceed to Pay</button>
                    </div>    
                </div>
            </div>';
                    
        }
        echo'</form>';
        
    } else {
        echo ' 
        <div class="container h-100 py-1">
        <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-sm-10 col-md-10 col-lg-10">
        <div class="card">
            <div class="card-body row">
            <h3 class="">The cart is empty :( Buy something pls, I have a family to feed.</h3>                                                
            </div>                                   
            </div>
            </div>
            </div>
            </div>';
        }        
        
        echo ' </body></html>';
    } else {
        header("Location: ../index.php");
}

