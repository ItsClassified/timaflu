
<?php
require('functions.php');

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
if(isset($_POST['getcustomers'])) {
    GetCustomers($_POST['phone'], $_POST['name']);
}

if(isset($_POST['selectcustomer'])) {
    getCustomerInfo($_POST['selectcustomer']);
}

if(isset($_POST['getorderinfo'])) {
    echo "<div class='card'>" ;
        echo "<table class='stats'>";
            echo "<tr>";
                echo "<th>Name</th>";
                echo "<th>Stock</th>";
                echo "<th>Strength</th>";
                echo "<th>Parcel Size</th>";
                echo "<th>Price</th>";
            echo "</tr>";
            $arr = array(0); // dit wordt dus een SESSION array
            // $arr = $_SESSION['orderlist'];
            foreach ($arr as &$value) {
                echo GetProductInfo($value);
            }
        echo "</table>";
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
                echo GetProducts($_POST['start'], $_POST['end'], $_POST['name'], $_POST['id']);
        echo "</table>";
    echo "</div>";
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