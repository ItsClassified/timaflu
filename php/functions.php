<?php
session_start();

function ConnectDatabase() {
    // $db = new PDO('mysql:host=databases.aii.avans.nl;dbname=mcbeurde1_db;charset=utf8mb4', 'mcbeurde1', 'Ab12345');
    // $db = new PDO('mysql:host=databases.aii.avans.nl;dbname=mcbeurde1_db2;charset=utf8mb4', 'mcbeurde1', 'Ab12345');
    $db = new PDO('mysql:host=db.gatherstuff.com;dbname=md422083db395722;charset=utf8mb4', 'md422083db395722', '8NTLTJYD');
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
                SUM((oi.price * (oi.delivered_quantity - oi.invoiced_quantity))) AS price
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
            echo "<th><span>ID</span></th>";
            echo "<th><span>Costumer name</span></th>";
            echo "<th><span>Completed for</span></th>";
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
// ALL THE PHP NEEDED FOR THE BILLING_STEPA1.PHP
////////////////////////////////////////////////////////////////////////////
function GetInvoiceItems($order_id) {
    $db = ConnectDatabase();
    
        $sql = "SELECT 
                    p.name name,
                    (oi.delivered_quantity - oi.invoiced_quantity) quantity,
                    oi.price price,
                    p.parcel_size psize,
                    (oi.price * (oi.delivered_quantity - oi.invoiced_quantity)) total
                FROM
                    orders o
                        INNER JOIN
                    order_items oi ON o.id = oi.order_id
                        INNER JOIN
                    products p ON p.id = oi.product_id
                WHERE
                    o.id = $order_id";
    
        $result = $db->prepare($sql);
        $result->execute();
    
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        echo "<table class='stats sortable'>";
        echo "<col style='width:57%'>";
        echo "<col style='width:8%'>";
        echo "<col style='width:15%'>";
        echo "<col style='width:15%'>";
        echo "<thead>";
            echo "<tr>";
                echo "<th><span>Product</span></th>";
                echo "<th><span>Qt.</span></th>";
                echo "<th><span>Price/p</span></th>";
                echo "<th><span>Total</span></th>";
            echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        for ($i=0; $i < sizeof($rows); $i++) {
            echo "<tr>";
            echo "<td><span>" . $rows[$i]['name'] . " (" . $rows[$i]['psize'] . ")</span></td>";
            echo "<td><span>" . $rows[$i]['quantity'] . "</span></td>";
            echo "<td><span>&#8364; " . $rows[$i]['price'] . "</span></td>";
            echo "<td><span>&#8364; " . $rows[$i]['total'] . "</span></td>";
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
    echo "<footer><label class='message correct clickable' OnClick='ConfirmCustomer(this)' id='select' value='" . $customer_id . "'>Confirm</label><label class='message warn clickable'>Save</label><label OnClick='RemoveCustomer(this)' id='" . $customer_id . "' class='message correct clickable'>Charts</label></footer>";
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
        echo "<footer><label class='message correct clickable' OnClick='EditProductInfo(this)' id='edit' value='" . $productid . "'>Edit</label><label class='message warn clickable'>Finish &amp; Save</label><label OnClick='ShowChartsStock(this)' id='" . $productid . "' class='message correct clickable'>Charts</label></footer>";
}

function StockInfoActiveIngredient($name, $id) {
    $db = ConnectDatabase();

    $sql = "SELECT 
                ai.name AS ainame,
                ai.id AS aiid,
                p.strength_quantity AS pstrength,
                su.unit AS sunit,
                ai.minimum_stock AS minstock,
                COUNT(DISTINCT (p.manufacturer_id)) AS nrman,
                SUM(s.stock * p.parcel_size) AS curstock
            FROM
                active_ingredients ai
                    LEFT JOIN
                products p ON ai.id = p.active_ingredient_id
                    LEFT JOIN
                stock s ON p.id = s.product_id
                    LEFT JOIN
                strength_units su ON su.id = p.strength_unit
            WHERE s.current = 1
            GROUP BY ai.id , p.strength_quantity
            ";

    $result = $db->prepare($sql);
    $result->execute();

    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    echo "<table class='stats sortable'>";
    echo "<col style='width:40%'>";
    echo "<col style='width:25%'>";
    echo "<col style='width:15%'>";
    echo "<col style='width:10%'>";
    echo "<col style='width:15%'>";
    echo "<thead>";
        echo "<tr>";
            echo "<th><span>Active ingredient</span></th>";
            echo "<th><span>Strength</span></th>";
            echo "<th><span>No. of manufacturers</span></th>";
            echo "<th><span>Current stock</span></th>";
            echo "<th><span>Minimum stock</span></th>";
        echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    for ($i=0; $i < sizeof($rows); $i++) {
        echo "<tr OnClick='SelectProduct(this)' id='". $rows[$i]['aiid'] . "' value='". $rows[$i]['pstrength'] . "'>";
            echo "<td><span>" . $rows[$i]['ainame'] . "</span></td>";
            echo "<td><span>" . $rows[$i]['pstrength'] . ' ' . $rows[$i]['sunit'] . "</span></td>";
            echo "<td><span>" . $rows[$i]['nrman'] . "</span></td>";
            echo "<td><span>" . $rows[$i]['minstock'] . "</span></td>"; // TODO fix date
            if($rows[$i]['curstock'] <= $rows[$i]['minstock']){
                echo "<td><div class='switcher red'><span class='blinker'>" . $rows[$i]['curstock'] . "</span></div></td>";
            } else{
                echo "<td><div class='switcher grey'><span>" . $rows[$i]['curstock'] . "</span></div></td>";
            }
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
}

function GetManufacturerList($aiid, $strengthquantity) {
    $db = ConnectDatabase();

    $sql = "SELECT 
                m.id AS mid,
                p.name AS pname,
                p.strength_quantity AS pstrength,
                su.unit AS sunit,
                p.parcel_size AS psize,
                (s.stock * p.parcel_size) AS curstock,
                p.minimum_stock AS minstock,
                ppl.price AS purprice,
                m.name AS manname
            FROM
                products p
                    LEFT JOIN
                strength_units su ON p.strength_unit = su.id
                    LEFT JOIN
                stock s ON p.id = s.product_id
                    LEFT JOIN
                purchase_price_loggs ppl ON ppl.product_id = p.id
                    LEFT JOIN
                manufacturers m ON m.id = p.manufacturer_id
            WHERE
                (s.current = 1 OR s.current IS NULL)
                    AND (ppl.current = 1 OR ppl.current IS NULL)
                    AND p.active_ingredient_id = $aiid
                    AND p.strength_quantity = $strengthquantity
            GROUP BY p.id
            ORDER BY ppl.price ASC
            ";

    $result = $db->prepare($sql);
    $result->execute();

    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    echo "<table class='stats sortable'>";
    echo "<col style='width:30%'>";
    echo "<col style='width:15%'>";
    echo "<col style='width:10%'>";
    echo "<col style='width:10%'>";
    echo "<col style='width:10%'>";
    echo "<col style='width:25%'>";
    echo "<thead>";
        echo "<tr>";
            echo "<th><span>Product name</span></th>";
            echo "<th><span>Strength</span></th>";
            echo "<th><span>Parcel</span></th>";
            echo "<th><span>Current stock</span></th>";
            echo "<th><span>Purchase price</span></th>";
            echo "<th><span>Manufacturer</span></th>";
        echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    for ($i=0; $i < sizeof($rows); $i++) {
        echo "<tr>";
            echo "<td><span>" . $rows[$i]['pname'] . "</span></td>";
            echo "<td><span>" . $rows[$i]['pstrength'] . ' ' . $rows[$i]['sunit'] . "</span></td>";
            echo "<td><span>" . $rows[$i]['psize'] . "</span></td>";
            echo "<td><span>" . $rows[$i]['curstock'] . "</span></td>";
            echo "<td><span>" . $rows[$i]['purprice'] . "</span></td>";
            echo "<td><span>" . $rows[$i]['manname'] . "</span></div></td>";
        echo "</tr>";
    }

    $_SESSION['mid'] = $rows[0]['mid'];
    echo "</tbody>";
    echo "</table>";
}

function GetManufacturerInfo($id) {
    $db = ConnectDatabase();

    $sql = "SELECT 
                m.name AS name,
                m.email AS email,
                m.phone AS phone,
                m.url AS url
            FROM
                manufacturers m
            WHERE m.id = $id
            ";

    $result = $db->prepare($sql);
    $result->execute();

    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    echo "<table class='stats sortable'>";
    echo "<col style='width:30%'>";
    echo "<col style='width:70%'>";
    echo "<tbody>";
    for ($i=0; $i < sizeof($rows); $i++) { 
        echo "<tr>";
            echo "<td><span>Company</span></td>";
            echo "<td><span>" . $rows[$i]['name'] . "</span></td>";
        echo "</tr>";
        echo "<tr>";
                echo "<td><span>Email</span></td>";
                echo "<td><span>" . $rows[$i]['email'] . "</span></td>";
        echo "</tr>";
        echo "<tr>";
                echo "<td><span>Phone</span></td>";
                echo "<td><span>" . $rows[$i]['phone'] . "</span></td>";
        echo "</tr>";
        echo "<tr>";
                echo "<td><span>URL</span></td>";
                echo "<td><span>" . $rows[$i]['url'] . "</span></td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
}

?>