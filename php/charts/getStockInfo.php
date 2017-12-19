<?php 
require('../functions.php');

$json['cols'][] = array('id' => "", 'label' => 'Someting else', 'pattern' => "", 'type' => 'string');
$json['cols'][] = array('id' => "", 'label' => 'Stock', 'pattern' => "", 'type' => 'number');

$db = ConnectDatabase();

$result = $db->prepare("SELECT * FROM stock WHERE product_id = 16 ORDER BY date ASC");
$result->execute();

$rows = $result->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < sizeof($rows); $i++) {
    $date = new DateTime($rows[$i]['date']) ;  
    $date = $date->format('m-d');
    $json['rows'][]['c'] = array(array("v" => $rows[$i]['stock'], "f" => $date),array("v" => $rows[$i]['stock'], "f" => $rows[$i]['stock']));
}


echo json_encode($json);
?>
