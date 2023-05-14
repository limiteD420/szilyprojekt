<?php
require("./database.php");
require("./methods.php");
session_start();

$isAdmin = DB::query('SELECT adminlevel FROM users WHERE id = :id', array(":id" => $_SESSION["userId"][0]["id"]));

if(isset($_SESSION["loggedIn"]) && ($isAdmin[0]["adminlevel"] == 1))
{
    
    foreach ($_POST as $key => $value) {
        if (str_starts_with($key, 'setDelivered-')) {
            $step = explode('-', $key)[1];            
            DB::query('UPDATE orders SET delivery_status = "Delivered" WHERE order_id = :order_id', array(":order_id" => $step));                 

            break;
        }
    } 

    echoHeader("Admin Panel");
    buttons();
    echo'<form method="post" action="admin.php">';
    DB::getOrders();
    echo'</form>';
    
    
}
else
{
    header("Location: ../php/webshop.php");
}
