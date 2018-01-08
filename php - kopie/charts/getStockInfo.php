<?php 
require('../functions.php');

$db = ConnectDatabase();
$productid = $_POST['id'];

$result = $db->prepare("SELECT s.stock as stock, s.date as date, p.name as pname FROM stock as s INNER JOIN products as p ON p.id = s.product_id WHERE product_id = $productid ORDER BY date ASC");
$result->execute();

$rows = $result->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < sizeof($rows); $i++) {
    $date = new DateTime($rows[$i]['date']) ;  
    $date = $date->format('m-d');
    $json['rows'][]['c'] = array(array("v" => $rows[$i]['stock'], "f" => $date),array("v" => $rows[$i]['stock'], "f" => $rows[$i]['stock']));
}

$json['cols'][] = array('id' => "", 'label' => 'Time', 'pattern' => "", 'type' => 'string');
$json['cols'][] = array('id' => "", 'label' => $rows[0]['pname'], 'pattern' => "", 'type' => 'number');

echo json_encode($json);
?>
