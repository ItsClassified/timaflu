<?php

function ConnectDatabase() {
    // $db = new PDO('mysql:host=databases.aii.avans.nl;dbname=mcbeurde1_db;charset=utf8mb4', 'mcbeurde1', 'Ab12345');
    $db = new PDO('mysql:host=localhost;dbname=timaflu;charset=utf8mb4', 'root', '');

    return $db;
}

function GetStockInfo($date, $start, $end, $search){
    $db = ConnectDatabase();

    $sql = "SELECT p.name AS pname,
        p.strength_quantity AS pstrength,
        su.unit AS sunit,
        SUM(s.stock) AS stock,
        s.product_id AS sproduct_id
    FROM stock AS s
    INNER JOIN products AS p
        ON s.product_id = p.id
    INNER JOIN strength_units AS su
        ON p.strength_unit = su.id
    WHERE current = 1";
        
    if($search != ' '){
        $sql = $sql . " AND p.name LIKE '%$search%' ";
    }

    $sql = $sql . " GROUP BY product_id LIMIT $start,$end";


    // $sql = "SELECT * FROM products AS p WHERE ?name? ";
    
    // if($name != ' '){
    //     //replace ?name? met "p.name LIKE %$name"
    // }

    // Select all the absic stock data we need
    // $result = $db->prepare("SELECT p.name AS pname,
    //                             p.strength_quantity AS pstrength,
    //                             su.unit AS sunit,
    //                             SUM(s.stock) AS stock,
    //                             s.product_id AS sproduct_id
    //                         FROM stock AS s
    //                         INNER JOIN products AS p
    //                             ON s.product_id = p.id
    //                         INNER JOIN strength_units AS su
    //                             ON p.strength_unit = su.id
    //                         WHERE current = 1
    //                             GROUP BY product_id LIMIT $start,$end"); // $start en $end are given with the fucntion, this makes it possible to show 10 and when you press NEXT it shows the next 10. 0-10/10-20/20-30
    
    $result = $db->prepare($sql);
    $result->execute();

    // Fetch all the data into $rows
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    echo "<table class='stats'>";
        echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>Stock</th>";
            echo "<th>Location</th>";
            echo "<th>Strength</th>";
        echo "</tr>";
    // Loop trough rows so we get all the data we need (incase there are 2 stock datas)
    for ($i=0; $i < sizeof($rows); $i++) {
        // Add id=product_id so we can do something with that when we click it with javascript
        echo "<tr id=\"" . $rows[$i]['sproduct_id'] . "\" OnClick=\"ShowProductInfo(this)\">";
        echo "<td>" . $rows[$i]['pname'] . "</td>";
        echo "<td>" . $rows[$i]['stock'] . "</td>";
        echo "<td> - </td>";
        echo "<td>" . $rows[$i]['pstrength'] . ' ' . $rows[$i]['sunit'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function GetAmountOfStockPages(){
    $db = ConnectDatabase();

    // Get all the stock data dat is being used
    $result = $db->prepare("SELECT SUM(stock) FROM stock WHERE current = 1 GROUP BY product_id");                    
    $result->execute();
    
    // Count the amount of rows
    $row_count = $result->rowCount();

    return $row_count;  
}

function SaveStockInfoProduct($productid, $date, $stock) {
    $db = ConnectDatabase();
    // Select the information about the stock that is getting eddited at the moment
    $result = $db->prepare("SELECT * FROM stock WHERE current = 1 AND product_id = $productid AND date = '$date'");
    $result->execute();
    // Fetch results into row
    $row = $result->fetch();

    // Get data as strings, makes it easier :)
    $manufactur = $row['manufacturer_id'];
    $expiry_date = $row['expiry_date'];
    $price = $row['price'];
    $oldstock = $row['stock'];

    // If old stock is the same as the new stock, do nothing
    if($oldstock == $stock) GetStockInfoProduct($productid); return;
    
    // Insert a new entry into the database using the data we collected earlier from our old stock data (use new stock tho ;)
    $db->exec("INSERT INTO stock (product_id, date, manufacturer_id, expiry_date, price, current, stock) VALUES('$productid', CURRENT_TIMESTAMP, '$manufactur', '$expiry_date', '$price', '1', '$stock')");

    // Update current to 0 on the old stock data but keep it in the database , this way we can keep track of everything :)
    $db->exec("UPDATE stock SET current = 0, date = date WHERE current = 1 AND product_id = '$productid' AND date = '$date'") or die(print_r($db->errorInfo(), true)); 

    // Print stockinfo for the product again so it looks nice :)
    GetStockInfoProduct($productid);
}

function GetStockInfoProduct($productid){
    $db = ConnectDatabase();

    // Get all the info we need for the product
    $result = $db->prepare("SELECT p.name AS pname,
                                p.strength_quantity AS pstrength,
                                p.parcel_size AS psize,
                                p.price AS price,
                                p.manufacturer_id AS pmanufacturer,
                                m.name AS mname,
                                su.unit AS sunit,
                                s.stock AS stock,
                                s.date AS sdate,
                                s.price AS sprice,
                                s.expiry_date AS sexpirydate
                            FROM stock AS s
                            INNER JOIN products AS p
                                ON s.product_id = p.id
                            INNER JOIN strength_units AS su
                                ON p.strength_unit = su.id
                            INNER JOIN manufacturers AS m
                                ON m.id = p.manufacturer_id
                            WHERE current = 1 AND s.product_id = $productid");
    $result->execute();

    // Fetch all the data into rows
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    echo "<div class='card'>" ;
    echo "<table class='stats'>";
        echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>Stock</th>";
            echo "<th>Location</th>";
            echo "<th>Strength</th>";
            echo "<th>Date</th>";
            echo "<th>Expiry Date</th>";
            echo "<th>Order Price</th>";
        echo "</tr>";
    // Loop trough rows so we get all the info we need (if there are 2 differen stock datas for 1 product, different orders.)
    for ($i=0; $i < sizeof($rows); $i++) {
        // Print the table rows with the needed information
        echo "<tr>";
            echo "<td>" . $rows[$i]['pname'] . "</td>";
            // Set class to stock so we can call on it later with javascript , add date (PK for the record) and the value stock so we can use that later aswell :)
            echo "<td class='stock' id='" . $rows[$i]['sdate'] . "' value='" . $rows[$i]['stock'] . "'>" . $rows[$i]['stock'] . "</td>";
            echo "<td> - </td>";
            echo "<td>" . $rows[$i]['pstrength'] . ' ' . $rows[$i]['sunit'] . "</td>";
            echo "<td class='date'>" . $rows[$i]['sdate'] . "</td>";
            echo "<td>" . $rows[$i]['sexpirydate'] . "</td>";
            echo "<td>" . $rows[$i]['sprice'] . "</td>";
        echo "</tr>";
    }
        echo "<table class='stats'>";
            echo "<tr>";
                echo "<th>Sell Price</th>";
                echo "<th>Parcel Size</th>";
                echo "<th>Manufacturer</th>";
            echo "<tr>";
            echo "<tr>";
                // This information is always the same, so we can just sue $rows[0].
                echo "<td>" . $rows[0]['price'] . "</td>";
                echo "<td>" . $rows[0]['psize'] . "</td>";
                echo "<td>" . $rows[0]['mname'] . "</td>";
            echo "<tr>";
        echo "<table class='stats'>";
        // Print the edit/close/delete buttons, add EditProductInfo to onlcick so it does something when we click it later on
        echo "<footer><label class='message correct' OnClick='EditProductInfo(this)' id='edit' value='" . $productid . "'>Edit</label><label class='message warn'>Close</label><label class='message error'>Delete</label></footer>";
}

?>




<?php
// function GetProductInfo($productid, $asked){
    //     $db = ConnectDatabase();
    
    //     if($asked == "active_ingredient"){
    //         $result = $db->prepare("SELECT act_ing.name AS active_ingredient FROM products AS p INNER JOIN active_ingredients AS act_ing ON p.active_ingredient_id = act_ing.id WHERE p.id = $productid");        
    //     } else {
    //         $result = $db->prepare("SELECT * FROM products WHERE id = $productid");        
    //     }
        
    //     $result->execute();
    
    //     $row = $result->fetch();
    
    //     return $row[$asked];
    // }
    // /**
    //  * Function that returns an object with all the information from an order.
    //  */
    // function GetOrderInfo($orderid){
    //     $db = ConnectDatabase();
    
    //     $result = $db->prepare("SELECT 
    //                                 customer_id,
    //                                 status_id,
    //                                 employee_id,
    //                                 order_date,
    //                                 c.name AS customer_name,
    //                                 c.phone AS customer_phone,
    //                                 c.contact AS customer_contact,
    //                                 c.url AS customer_url,
    //                                 s.name AS status_name,
    //                                 e.id AS employee_id,
    //                                 e.full_name AS employee_name
    //                             FROM
    //                                 orders AS o
    //                                     INNER JOIN
    //                                 customers AS c ON o.customer_id = c.id
    //                                     INNER JOIN
    //                                 order_status AS s ON o.status_id = s.id
    //                                     INNER JOIN
    //                                 employees AS e ON o.employee_id = e.id
    //                             WHERE
    //                                 o.id = $orderid");
                                    
        
    //     $result->execute();
    
    //     $row = $result->fetch();
    
    //     $info = new stdClass();
    //     $info->customer_id = $row['customer_id'];
    //     $info->status_id = $row['status_id'];
    //     $info->employee_id = $row['employee_id'];
    //     $info->order_date = $row['order_date'];
    //     $info->customer_name = $row['customer_name'];
    //     $info->customer_phone = $row['customer_phone'];
    //     $info->customer_contact = $row['customer_contact'];
    //     $info->customer_url = $row['customer_url'];
    //     $info->status_name = $row['status_name'];
    //     $info->employee_name = $row['employee_name'];
    
    //     return $info;
    // }
    ?>