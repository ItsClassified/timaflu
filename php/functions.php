<?php

function ConnectDatabase() {
    $db = new PDO('mysql:host=databases.aii.avans.nl;dbname=mcbeurde1_db;charset=utf8mb4', 'mcbeurde1', 'Ab12345');
    // $db = new PDO('mysql:host=localhost;dbname=timaflu;charset=utf8mb4', 'root', '');

    return $db;
}

function GetProductInfo($productid, $asked){
    $db = ConnectDatabase();

    if($asked == "active_ingredient"){
        $result = $db->prepare("SELECT act_ing.name AS active_ingredient FROM products AS p INNER JOIN active_ingredients AS act_ing ON p.active_ingredient_id = act_ing.id WHERE p.id = $productid");        
    } else {
        $result = $db->prepare("SELECT * FROM products WHERE id = $productid");        
    }
    
    $result->execute();

    $row = $result->fetch();

    return $row[$asked];
}
/**
 * Function that returns an object with all the information from an order.
 */
function GetOrderInfo($orderid){
    $db = ConnectDatabase();

    $result = $db->prepare("SELECT 
                                customer_id,
                                status_id,
                                employee_id,
                                order_date,
                                c.name AS customer_name,
                                c.phone AS customer_phone,
                                c.contact AS customer_contact,
                                c.url AS customer_url,
                                s.name AS status_name,
                                e.id AS employee_id,
                                e.full_name AS employee_name
                            FROM
                                orders AS o
                                    INNER JOIN
                                customers AS c ON o.customer_id = c.id
                                    INNER JOIN
                                order_status AS s ON o.status_id = s.id
                                    INNER JOIN
                                employees AS e ON o.employee_id = e.id
                            WHERE
                                o.id = $orderid");
                                
    
    $result->execute();

    $row = $result->fetch();

    $info = new stdClass();
    $info->customer_id = $row['customer_id'];
    $info->status_id = $row['status_id'];
    $info->employee_id = $row['employee_id'];
    $info->order_date = $row['order_date'];
    $info->customer_name = $row['customer_name'];
    $info->customer_phone = $row['customer_phone'];
    $info->customer_contact = $row['customer_contact'];
    $info->customer_url = $row['customer_url'];
    $info->status_name = $row['status_name'];
    $info->employee_name = $row['employee_name'];

    return $info;
}



function GetStockInfo($date, $start, $end){
    $db = ConnectDatabase();

    $result = $db->prepare("SELECT p.name AS pname,
                                   p.strength_quantity AS pstrength,
                                   su.unit AS sunit,
                                   SUM(s.stock) AS stock,
                                   s.product_id AS sproduct_id
                                   FROM stock AS s
                                   INNER JOIN products AS p
                                   ON s.product_id = p.id
                                   INNER JOIN strength_units AS su
                                   ON p.strength_unit = su.id
                                   WHERE current = 1
                                   GROUP BY product_id LIMIT $start,$end");                    
    
    $result->execute();

    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    echo "<table class='stats'>";
        echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>Stock</th>";
            echo "<th>Location</th>";
            echo "<th>Strength</th>";
        echo "</tr>";
    for ($i=0; $i < sizeof($rows); $i++) {
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

    $result = $db->prepare("SELECT SUM(stock) FROM stock WHERE current = 1 GROUP BY product_id");                    

    $result->execute();

    $row_count = $result->rowCount();

    return $row_count;  
}

function GetStockInfoProduct($productid){
    $db = ConnectDatabase();

    $result = $db->prepare("SELECT p.name AS pname,
                                   p.strength_quantity AS pstrength,
                                   p.parcel_size AS psize,
                                   p.price AS price,
                                   p.manufacturer_id AS pmanufacturer,
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
                                   WHERE current = 1
                                   AND s.product_id = $productid");                    
    
    $result->execute();

    $rows = $result->fetchAll(PDO::FETCH_ASSOC);

    echo "<table class='stats'>";
        echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>Stock</th>";
            echo "<th>Location</th>";
            echo "<th>Strength</th>";
            echo "<th>Order Date</th>";
            echo "<th>Expiry Date</th>";
            echo "<th>Order Price</th>";
        echo "</tr>";
    for ($i=0; $i < sizeof($rows); $i++) {
        echo "<tr>";
            echo "<td>" . $rows[$i]['pname'] . "</td>";
            echo "<td>" . $rows[$i]['stock'] . "</td>";
            echo "<td> - </td>";
            echo "<td>" . $rows[$i]['pstrength'] . ' ' . $rows[$i]['sunit'] . "</td>";
            echo "<td>" . $rows[$i]['sdate'] . "</td>";
            echo "<td>" . $rows[$i]['sexpirydate'] . "</td>";
            echo "<td>" . $rows[$i]['sprice'] . "</td>";
        echo "</tr>";
    }
        echo "<table class='stats'>";
            echo "<tr>";
                echo "<th>Price</th>";
                echo "<th>Parcel Size</th>";
                echo "<th>Manufacturer</th>";
            echo "<tr>";
            echo "<tr>";
                echo "<td>" . $rows[0]['price'] . "</td>";
                echo "<td>" . $rows[0]['psize'] . "</td>";
                echo "<td>" . $rows[0]['pmanufacturer'] . "</td>";
            echo "<tr>";
        echo "<table class='stats'>";
}
?>