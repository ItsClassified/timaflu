
<?php
require('functions.php');
/**
 * Ajax needed for billing_Step1.php
 */
if(isset($_POST['getorders'])){
    GetOrders($_POST['name'], $_POST['id']);
}

if(isset($_POST['selectorder'])){
    $_SESSION['billing_order'] = $_POST['id'];
}


if(isset($_POST['send_invoice'])){
    require_once "Mail.php";
    require_once "Mail/mime.php";
    
    $from = '<support@gatherstuff.com>';
    $to = 'karsmiesen@ziggo.nl';
    $subject = 'Factuur: <FactuurID>';
    
    $headers = array(
        'From' => $from,
        'To' => $to,
        'Subject' => $subject
    );
    
    $text = "";
    $html = "Hierbij ontvangt u van ons een factuur als bijlage.<br>Wij verzoeken u het factuurbedrag binnen 30 dagen na factuurdatum over te maken onder vermelding van factuur- en debiteurnummer.<br><br>
    <font color='grey'>Hereby enclosed you receive an invoice.<br>We ask you to transfer the invoice amount within 30 days after invoicedate, stating invoice- and customernumber.</font><br><br>
    Met vriendelijke groet, With kind regards,<br><br>
    Timaflu";
    
    $mime = new Mail_mime();
    $mime -> setTXTBody($text);
    $mime -> setHTMLBody($html);
    $mime -> addAttachment('../pdf/order_' . $_POST['id'] . '.pdf', 'application/pdf', 'order_' . $_POST['id'] . '.pdf',true,'base64', 'attachment');
    $body = $mime -> get();
    $headers = $mime -> headers($headers);
    
    $smtp = Mail::factory('smtp', array(
            'host' => 'smtp.mijndomein.nl',
            'port' => '26',
            'auth' => true,
            'username' => 'support@gatherstuff.com',
            'password' => '1_Timaflu'
        ));
    
    $mail = $smtp->send($to, $headers, $body);
    
    if (PEAR::isError($mail)) {
        echo('<p>' . $mail->getMessage() . '</p>');
    } else {
        echo('<p>Message successfully sent!</p>');
    }
}

/**
 * Ajax needed for stock.php
 */
if(isset($_POST['product_id'])) {
    GetStockInfoProduct($_POST['product_id']);
}

if(isset($_POST['stockinfo'])) {
    GetStockInfo(0, $_POST['start'], $_POST['end'], $_POST['search']);
}

if(isset($_POST['save_productinfo'])) {
    echo SaveStockInfoProduct($_POST['save_productinfo'], $_POST['date'], $_POST['stock']);
}

if(isset($_POST['stockpages'])) {
    $items = GetAmountOfPages("SELECT SUM(stock) FROM stock WHERE current = 1 GROUP BY product_id");
    
    for ($i=0; $i < $items / 10; $i++) {
        $j = $i + 1;
        $k = $i * 10;
        if($k == $_POST['stockpages']){
            echo "<label id='$k' OnClick='GoToPage(this)'><b>$j </b></label>";
        } else {
            echo "<label id='$k' OnClick='GoToPage(this)'>$j </label>";
        }
    }
}

/**
 * Ajax needed for order.php
 */
// CUSTOMER STUFF
if(isset($_POST['getcustomers'])) {
    GetCustomers($_POST['phone'], $_POST['name']);
}

if(isset($_POST['selectcustomer'])) {
    getCustomerInfo($_POST['selectcustomer']);
}

if(isset($_POST['confirmcustomer'])) {

    $_SESSION['customer'] = $_POST['confirmcustomer'];
    
    $_SESSION['orderlist'] = array();
}

if(isset($_POST['removecustomer'])) {
    session_destroy(); // destroy the session so the user can start over
}

// ORDER STUFF
if(isset($_POST['getorderinfo'])) {
    echo "<div class='card'>" ;
        echo "<table class='stats'>";
            echo "<tr>";
                echo "<th>Name</th>";
                echo "<th>Stock</th>";
                echo "<th>Strength</th>";
                echo "<th>Parcel Size</th>";
                echo "<th>Price a piese</th>";
                echo "<th>Amount</th>";
                echo "<th>Total Price</th>";
            echo "</tr>";
            $size = sizeof($_SESSION['orderlist']);
            $start = $_POST['start'];

            if(($start + 10) > $size) $end = $size;
            else $end = $start + 10;

            for ($i=$start; $i < $end; $i++) { 
                echo "<tr>";
                    echo GetProductInfo($_SESSION['orderlist'][$i]['id']);
                    echo "<td>" . $_SESSION['orderlist'][$i]['amount'] . "</td>";
                    echo "<td>" . $_SESSION['orderlist'][$i]['price'] . "</td>";
                echo "</tr>";
            }
        echo "</table>"; ?>
            <footer>
                <label OnClick='PreviousOrder()'>Previous</label>
                    <label id='pages'>
                        <?php
                            $items = sizeof($_SESSION['orderlist']);
                            for ($i=0; $i < $items / 10; $i++) {
                                $j = $i + 1;
                                $k = $i * 10;
                                if ($k == $_POST['start']) {
                                    echo "<label id='$k' OnClick='GoToOrderPage(this)'><b>$j</b> </label>";
                                } else {
                                    echo "<label id='$k' OnClick='GoToOrderPage(this)'>$j </label>";
                                }  
                            }
                        ?>
                    </label>
                <label OnClick='NextOrder()'>Next</label>
            </footer>
        </div>
    <?php
}

// PRODUCTS STUFF
if(isset($_POST['confirmproduct'])) {
    $orderlist = $_SESSION['orderlist'];

    $id = $_POST['confirmproduct'];
    $amount = $_POST['amount'];
    $price = $_POST['price'];

    for ($i=0; $i < sizeof($orderlist); $i++) { 
        if($orderlist[$i]['id'] == $id){
            $orderlist[$i]['amount'] = $amount;
            $orderlist[$i]['price'] = $price * $amount;
            $_SESSION['orderlist'] = $orderlist;
            return;
        }
    }
    
    $size = sizeof($orderlist);
    $orderlist[$size]['id'] = $id;
    $orderlist[$size]['amount'] = $amount;
    $orderlist[$size]['price'] = $price * $amount;

    $_SESSION['orderlist'] = $orderlist;
}

if(isset($_POST['selectproduct'])) {
    $amount = 0;
    $price = 0;
    
    for ($i=0; $i < sizeof($_SESSION['orderlist']); $i++) {
        if($_SESSION['orderlist'][$i]['id'] == $_POST['selectproduct']){
            $amount = $_SESSION['orderlist'][$i]['amount'];
            $price = $_SESSION['orderlist'][$i]['price'];
        }
    }

    echo "<div class='card'>" ;
        echo "<table class='stats'>";
            echo "<tr>";
                echo "<th>Name</th>";
                echo "<th>Stock</th>";
                echo "<th>Strength</th>";
                echo "<th>Parcel Size</th>";
                echo "<th>Price</th>";
                echo "<th>Amount</th>";
                echo "<th>Total Price</th>";
            echo "</tr>";
            echo "<tr>";
                echo GetProductInfo($_POST['selectproduct']);
                echo "<td><input type='number' id='product_amount' value='" . $amount . "'></td>";
                echo "<td id='product_price_total'>" . $price . "</td>";
            echo "</tr>";
        echo "</table>";
    echo "<footer><label>Decrease</label><label OnClick='ConfirmProduct(this)' id='add' value='" . $_POST['selectproduct'] . "'>Save</label><label>Increase</label></footer>";
echo "</div>";  
}

if(isset($_POST['getproducts'])) {
    echo "<div class='card'>" ;
        echo "<table class='stats'>";
            echo "<tr>";
                echo "<th>Name</th>";
                echo "<th>Stock</th>";
                echo "<th>Strength</th>";
                echo "<th>Parcel Size</th>";
                echo "<th>Price</th>";
            echo "</tr>";
                echo GetProducts($_POST['start'], $_POST['end'], $_POST['name'], $_POST['id'], $_POST['ingredient']);
        echo "</table>";
?>
        <footer>
            <label OnClick='Previous()'>Previous</label>
                <label id='pages'>
                    <?php
                        $name = $_POST['name'];
                        $id = $_POST['id'];
                        $items = GetAmountOfPages("SELECT * FROM products WHERE name LIKE '%$name%' AND id LIKE '%$id%'");
                        for ($i=0; $i < $items / 10; $i++) {
                            $j = $i + 1;
                            $k = $i * 10;
                            if ($k == $_POST['start']) {
                                echo "<label id='$k' OnClick='GoToPage(this)'><b>$j</b> </label>";
                            } else {
                                echo "<label id='$k' OnClick='GoToPage(this)'>$j </label>";
                            }  
                        }
                    ?>
                </label>
            <label OnClick='Next()'>Next</label>
        </footer>
    </div>
<?php
}





///////////////////////////////////////////////////////////////////////////////////////////////
// if(isset($_POST['maininfo'])) {
//     DrawTable("SELECT p.name AS Name,
//         p.strength_quantity AS Strength,
//         su.unit AS Strengt_Unit,
//         SUM(s.stock) AS Stock,
//         s.product_id AS Product_Id
//     FROM stock AS s
//     INNER JOIN products AS p
//         ON s.product_id = p.id
//     INNER JOIN strength_units AS su
//         ON p.strength_unit = su.id
//     WHERE current = 1 GROUP BY product_id LIMIT 0,10");
// }

// if(isset($_POST['iteminfo'])) {
//     echo "iteminfo";
// }

// if(isset($_POST['getpages'])) {
//     for ($i=0; $i < 100 / 10; $i++) {
//         $j = $i + 1;
//         $k = $i * 10;
//         if($k == $_POST['getpages']){
//             echo "<label id='$k' OnClick='GoToPage(this)'><b>$j </b></label>";
//         } else {
//             echo "<label id='$k' OnClick='GoToPage(this)'>$j </label>";
//         }
//     }
// }

?>