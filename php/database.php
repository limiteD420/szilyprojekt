<?php
// This static class connects to a DB, the class method makes querys from the DB. First parameter is for the SQL commands the second is for the values 
class DB
{
    private static $pdo;

    public static function connect()
    {
        $dsn = 'mysql:host=localhost;dbname=projekt_adatbazis';
        $username = 'root';
        $password = '';
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        self::$pdo = new PDO($dsn, $username, $password, $options);
    }

    public static function query($sql, $params = array()) // Protects against SQL injection with prepapred statements
    {   
        self::connect();
        $stmt = self::$pdo->prepare($sql); // Checks that the query is syntactically correct
        $stmt->execute($params); // Executes the query
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Returns an associative array
        $stmt->closeCursor(); // Frees up the connection to the server
        return $result;
    }

    public static function insertOrder($product_data, $overall_price)
    {
        // Extract product names and quantities from product data array
        $product_names = array();
        $product_quantities = array();
        foreach ($product_data as $product) {
            $product_names[] = $product['name'];
            $product_quantities[] = $product['quantity'];
        }
        
        // Insert order data into database
        $sql = "INSERT INTO orders (ordered_products, ordered_quantities, overall_price, delivery_status) VALUES (:ordered_products, :ordered_quantities, :overall_price, :delivery_status)";
        $params = array(
            ':ordered_products' => implode(',', $product_names),
            ':ordered_quantities' => implode(',', $product_quantities),
            ':overall_price' => $overall_price,
            ':delivery_status' => 'Pending'
        );
        self::query($sql, $params);
    }

    public static function getOrders()
    {
        self::connect();

        $sql = "SELECT * FROM orders";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Generate the table headers
        echo "<table class=\"table\">";
        echo "<tr><th>Order ID</th><th>Ordered Products</th><th>Ordered Quantities</th><th>Overall Price</th><th>Delivery Status</th><th></th></tr>";

        // Loop through each order and display the details in a table row
        
        foreach ($orders as $order) {
            echo "<tr>";
            echo "<td>" . $order['order_id'] . "</td>";
            echo "<td>" . $order['ordered_products'] . "</td>";
            echo "<td>" . $order['ordered_quantities'] . "</td>";
            echo "<td>" . $order['overall_price'] . "</td>";
            echo "<td>" . $order['delivery_status'] . "</td>";
            if($order["delivery_status"] == "Pending")
            {
                echo "<td><button name=\"setDelivered-". $order['order_id'] ."\" type=\"submit\" class=\"btn btn-danger btn-sm\">Delivered</button></td>";
                
            }
            //echo "<td><input name=\"setDeliveredById\" type=\"hidden\" value=\"" . $order['order_id'] . "\"></td>";
            echo "</tr>";
        }

        echo "</table>";
    }
    
}
