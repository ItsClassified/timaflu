<?php 
require('../functions.php');

$json['cols'][] = array('id' => "", 'label' => 'Product', 'pattern' => "", 'type' => 'string');
$json['cols'][] = array('id' => "", 'label' => 'Sold', 'pattern' => "", 'type' => 'number');

$db = ConnectDatabase();

$result = $db->prepare("SELECT 
                        p.name AS pname,
                        p.strength_quantity,
                        su.unit,
                        p.parcel_size,
                        SUM(quantity) as total_sold
                        FROM
                        products p
                            INNER JOIN
                        invoice_items ii ON p.id = ii.product_id
                            INNER JOIN
                        strength_units su ON p.strength_unit = su.id
                        GROUP BY p.id
                        ORDER BY total_sold DESC
                        LIMIT 5");
$result->execute();

$rows = $result->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < sizeof($rows); $i++) {
    $json['rows'][]['c'] = array(array("v" => $rows[$i]['pname'] . " (" . $rows[$i]['total_sold'] . ")", "f" => null),array("v" => intval($rows[$i]['total_sold']), "f" => null));
}


echo json_encode($json);
?>
