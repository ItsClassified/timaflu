<?php 
require('../functions.php');

$json['cols'][] = array('id' => "", 'label' => 'Product', 'pattern' => "", 'type' => 'string');
$json['cols'][] = array('id' => "", 'label' => 'Sold', 'pattern' => "", 'type' => 'number');

$db = ConnectDatabase();

$result = $db->prepare("SELECT os.name as name, COUNT(status_id) as total
FROM
    orders o
INNER JOIN order_status os
ON os.id = o.status_id
WHERE
    o.customer_id = 6
GROUP BY status_id");
$result->execute();

$rows = $result->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < sizeof($rows); $i++) {
    $json['rows'][]['c'] = array(array("v" => $rows[$i]['name'] . " (" . $rows[$i]['total'] . ")", "f" => null),array("v" => intval($rows[$i]['total']), "f" => null));
}


echo json_encode($json);
?>
