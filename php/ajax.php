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
?>