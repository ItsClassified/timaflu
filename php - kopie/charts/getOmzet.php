<?php 
require('../functions.php');

$json['cols'][] = array('id' => "", 'label' => 'Company', 'pattern' => "", 'type' => 'string');
$json['cols'][] = array('id' => "", 'label' => 'Annual Turnover', 'pattern' => "", 'type' => 'number');

$db = ConnectDatabase();

$result = $db->prepare("SELECT sum(i.final_price) AS annual_turnover, c.name AS Customer
                        FROM invoices AS i 
                        INNER JOIN payment_status AS ps
                        ON i.payment_status = ps.id
                        INNER JOIN orders AS o
                        ON o.id = i.order_id
                        INNER JOIN customers AS c
                        ON c.id = o.customer_id
                        WHERE ps.name = 'paid'
                        AND year(i.creation_date) = 2017
                        GROUP BY c.name");
$result->execute();

$rows = $result->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < sizeof($rows); $i++) {
    $json['rows'][]['c'] = array(array("v" => $rows[$i]['Customer'], "f" => null),array("v" => intval($rows[$i]['annual_turnover']), "f" => null));
}


echo json_encode($json);
?>


