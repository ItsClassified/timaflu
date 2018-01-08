<?php
session_start();

function ConnectDatabase() {
    // $db = new PDO('mysql:host=databases.aii.avans.nl;dbname=mcbeurde1_db;charset=utf8mb4', 'mcbeurde1', 'Ab12345');
    $db = new PDO('mysql:host=databases.aii.avans.nl;dbname=mcbeurde1_db2;charset=utf8mb4', 'mcbeurde1', 'Ab12345');
    // $db = new PDO('mysql:host=localhost;dbname=timaflu;charset=utf8mb4', 'root', '');

    return $db;
}

function GetAmountOfPages($sql){
    $db = ConnectDatabase();
    
    $result = $db->prepare($sql);                    
    $result->execute();
    
    // Count the amount of rows
    $row_count = $result->rowCount();

    return $row_count;  
}

////////////////////////////////////////////////////////////////////////////
// ALL THE PHP NEEDED FOR THE BILLING_STEP1.PHP
////////////////////////////////////////////////////////////////////////////
function GetOrders($name, $id) {
    $db = ConnectDatabase();

    $sql = "SELECT 
                o.id AS oid,
                c.name AS cname,
                o.completion_date AS ocompldate,
                s.name AS orderstatus,
                SUM((oi.price * oi.quantity)) AS price
            FROM
                orders as o
            INNER JOIN customers as c
                ON o.customer_id = c.id
            INNER JOIN order_items as oi
                ON oi.order_id = o.id
            INNER JOIN order_status as s
                ON s.id = o.status_id
            WHERE o.id LIKE '%$id%' AND c.name LIKE '%$name%'
            GROUP BY o.id
            ";

    $result = $db->prepare($sql);
    $result->execute();

    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    echo "<table class='stats sortable'>";
    echo "<col style='width:10%'>";
    echo "<col style='width:20%'>";
    echo "<col style='width:17%'>";
    echo "<col style='width:33%'>";
    echo "<col style='width:20%'>";
    echo "<thead>";
        echo "<tr>";
            echo "<th><span>Order ID</span></th>";
            echo "<th><span>Costumer name</span></th>";
            echo "<th><span>Order completed for</span></th>";
            echo "<th><span>Status</span></th>";
            echo "<th><span>First invoiced</span></th>";
        echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    for ($i=0; $i < sizeof($rows); $i++) {
    echo "<tr OnClick='SelectOrder(this)' id='". $rows[$i]['oid'] . "'>";
        echo "<td><span>" . $rows[$i]['oid'] . "</span></td>";
        echo "<td><span>" . $rows[$i]['cname'] . "</span></td>";
        echo "<td><div class='switcher red'><span>" . $rows[$i]['ocompldate'] . "</span></div></td>"; // TODO fix date
        if($rows[$i]['orderstatus'] == "completed"){
            echo "<td><div class='switcher green'><span>billable</span><div class='switcher white'><span>" . $rows[$i]['price'] . "</span></div></div></td>";
        } else if($rows[$i]['orderstatus'] == "open"){
            echo "<td><div class='switcher grey'><span>awaits completion </span><div class='switcher white'><span>-</span></div></div></td>";
        }
        echo "<td><div class='switcher orange'><span>17-12-2017 </span><div class='switcher white'><span>19 days ago</span></div></div></td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
}
////////////////////////////////////////////////////////////////////////////
// ALL THE PHP NEEDED FOR THE ORDER.PHP
////////////////////////////////////////////////////////////////////////////
function GetCustomers($phone, $name){
    $db = ConnectDatabase();

    $sql = "SELECT * FROM customers WHERE name LIKE '%$name%' AND phone LIKE '%$phone%' GROUP BY id LIMIT 0,20";

    $result = $db->prepare($sql);
    $result->execute();

    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    echo "<table class='stats'>";
        echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>Phone</th>";
            echo "<th>Contact</th>";
            echo "<th>URL</th>";
        echo "</tr>";
    for ($i=0; $i < sizeof($rows); $i++) {
        echo "<tr id=\"" . $rows[$i]['id'] . "\" OnClick=\"SelectCustomer(this)\">";
        echo "<td>" . $rows[$i]['name'] . "</td>";
        echo "<td>" . $rows[$i]['phone'] . "</td>";
        echo "<td>" . $rows[$i]['contact'] . "</td>";
        echo "<td>" . $rows[$i]['url'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function GetCustomerInfo($customer_id){
    $db = ConnectDatabase();

    $result = $db->prepare("SELECT * FROM customers WHERE id = $customer_id");
    $result->execute();

    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    echo "<div class='card'>" ;
    echo "<table class='stats'>";
        echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>Phone</th>";
            echo "<th>Contact</th>";
            echo "<th>URL</th>";
        echo "</tr>";
    for ($i=0; $i < sizeof($rows); $i++) {
        echo "<tr>";
            echo "<td>" . $rows[$i]['name'] . "</td>";
            echo "<td>" . $rows[$i]['phone'] . "</td>";
            echo "<td>" . $rows[$i]['contact'] . "</td>";
            echo "<td>" . $rows[$i]['url'] . "</td>";            
        echo "</tr>";
    }
    echo "<footer><label class='message correct clickable' OnClick='ConfirmCustomer(this)' id='select' value='" . $customer_id . "'>Confirm</label><label class='message warn clickable'>Close</label><label OnClick='RemoveCustomer(this)' id='" . $customer_id . "' class='message correct clickable'>Charts</label></footer>";
}

function GetProductInfo($product_id) {
    $db = ConnectDatabase();

    $result = $db->prepare("SELECT 
                                p.name AS name,
                                SUM(s.stock) AS stock,
                                strength_quantity AS strength,
                                su.unit AS sunit,
                                parcel_size AS psize,
                                pl.price AS price
                            FROM
                                products AS p
                                    INNER JOIN
                                price_loggs AS pl ON pl.product_id = p.id
                                    INNER JOIN
                                strength_units AS su ON su.id = p.strength_unit
                                    INNER JOIN
                                stock AS s ON s.product_id = p.id
                            WHERE
                                s.current = 1 AND p.current = 1
                                    AND p.id = $product_id GROUP BY s.product_id");
    $result->execute();

    $rows = $result->fetchAll(PDO::FETCH_ASSOC);

    echo "<td>" . $rows[0]['name'] . "</td>";
    echo "<td>" . $rows[0]['stock'] . "</td>";
    echo "<td>" . $rows[0]['strength'] . ' ' . $rows[0]['sunit'] . "</td>";
    echo "<td>" . $rows[0]['psize'] . "</td>";            
    echo "<td id='product_price' value='" . $rows[0]['price'] . "'>" . $rows[0]['price'] . "</td>";            
}


function GetProducts($start, $end, $name, $id, $ingredient) {
    $db = ConnectDatabase();

    $result = $db->prepare("SELECT
                                p.id AS productid,
                                p.name AS name,
                                SUM(s.stock) AS stock,
                                strength_quantity AS strength,
                                su.unit AS sunit,
                                parcel_size AS psize,
                                pl.price AS price
                            FROM
                                products AS p
                                    INNER JOIN
                                price_loggs AS pl ON pl.product_id = p.id
                                    INNER JOIN
                                strength_units AS su ON su.id = p.strength_unit
                                    INNER JOIN
                                stock AS s ON s.product_id = p.id
                                    INNER JOIN
                                active_ingredients AS ai ON p.active_ingredient_id = ai.id
                            WHERE
                                s.current = 1
                                AND p.current = 1
                                AND ai.name LIKE '%$ingredient%'
                                AND p.name LIKE '%$name%'
                                AND p.id LIKE '%$id%'
                            GROUP BY s.product_id
                            ORDER BY p.id LIMIT $start,$end");
    $result->execute();

    $rows = $result->fetchAll(PDO::FETCH_ASSOC);

    for ($i=0; $i < sizeof($rows); $i++) {
        echo "<tr OnClick='SelectProduct(this)' value='" . $rows[$i]['productid'] . "'>";
            echo "<td>" . $rows[$i]['name'] . "</td>";
            echo "<td>" . $rows[$i]['stock'] . "</td>";
            echo "<td>" . $rows[$i]['strength'] . ' ' . $rows[$i]['sunit'] . "</td>";
            echo "<td>" . $rows[$i]['psize'] . "</td>";            
            echo "<td>" . $rows[$i]['price'] . "</td>";   
        echo "</tr>";
    }
}

function AddItemToList($product_id, $amount) {

}

////////////////////////////////////////////////////////////////////////////
// ALL THE PHP NEEDED FOR THE STOCK.PHP
////////////////////////////////////////////////////////////////////////////
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
    WHERE s.current = 1";
        
    if($search != ' '){
        $sql = $sql . " AND p.name LIKE '%$search%'";
    }

    $sql = $sql . " GROUP BY product_id LIMIT $start,$end";

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
    if($oldstock == $stock) {
        GetStockInfoProduct($productid);
        return;
    }
    
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
                            WHERE s.current = 1 AND s.product_id = $productid");
    $result->execute();

    // Fetch all the data into rows
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    // Print basic information for the table wer are going to use
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
        echo "<footer><label class='message correct clickable' OnClick='EditProductInfo(this)' id='edit' value='" . $productid . "'>Edit</label><label class='message warn clickable'>Close</label><label OnClick='ShowChartsStock(this)' id='" . $productid . "' class='message correct clickable'>Charts</label></footer>";
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

    function DrawTable($query)
    {
        $db = ConnectDatabase();
             
            $result = $db->prepare($query);                           
            $result->execute();
            $colcount = $result->columnCount();
            
        
            echo "<table class='stats'>";
            echo "<tr>";
            for ($i = 0; $i < $colcount; $i++){
                $meta = $result->getColumnMeta($i)["name"];
                echo('<th>' . ucfirst($meta) . '</th>');
            }
            echo('</tr>');
        
    
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
            {
                echo('<tr>');
                for ($i = 0; $i < $colcount; $i++){
                    $meta = $result->getColumnMeta($i)["name"];
                    echo('<td>' . $row[$meta] . '</td>');
                }
                echo('</tr>');
            }
            echo "</table>";
        
    }    
?>