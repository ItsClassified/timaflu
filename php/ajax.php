<script src="js/charts.js"></script>

<?php
require('functions.php');

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
    $items = GetAmountOfStockPages();
    
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

if(isset($_POST['lol'])) {
    echo "LOL";
}
///////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['stockchart'])) {
    echo "<div class='row'>";
        echo "<div class='card cont12'>";
            echo "<header>";
                echo "<h4 class='title'>Stock over a period of time</h4>";
                echo "<p class='description'>Show the stock data for the currently selected product</p>";
            echo "</header>";
            echo "<div id='stockchart'></div>";
        echo "</div>";
    echo "</div>";
    ?>
    <script type="text/javascript"> google.charts.setOnLoadCallback(drawStockInfo); </script>
    <?php
}

///////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['maininfo'])) {
    DrawTable("SELECT p.name AS Name,
        p.strength_quantity AS Strength,
        su.unit AS Strengt_Unit,
        SUM(s.stock) AS Stock,
        s.product_id AS Product_Id
    FROM stock AS s
    INNER JOIN products AS p
        ON s.product_id = p.id
    INNER JOIN strength_units AS su
        ON p.strength_unit = su.id
    WHERE current = 1 GROUP BY product_id LIMIT 0,10");
}

if(isset($_POST['iteminfo'])) {
    echo "iteminfo";
}

if(isset($_POST['getpages'])) {
    for ($i=0; $i < 100 / 10; $i++) {
        $j = $i + 1;
        $k = $i * 10;
        if($k == $_POST['getpages']){
            echo "<label id='$k' OnClick='GoToPage(this)'><b>$j </b></label>";
        } else {
            echo "<label id='$k' OnClick='GoToPage(this)'>$j </label>";
        }
    }
}

?>