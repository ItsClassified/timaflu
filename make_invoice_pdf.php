<?php // CLEANED
require('php/fpdf.php');

include('php/functions.php');

    $db = ConnectDatabase();
    $order_id = $_SESSION['billing_order'];

    // Get customer information
    $sql = "SELECT c.*,  a.address FROM customers c
    INNER JOIN orders o ON c.id = o.customer_id
    INNER JOIN addresses a ON a.customer_id = c.id
    WHERE o.id = $order_id AND a.type_id = 2";

    $result = $db->prepare($sql);
    $result->execute();

    $customer = $result->fetchAll(PDO::FETCH_ASSOC);
    $customer_address = explode(",", $customer[0]['address']);

    // Get order information
    $sql = "SELECT 
                oi.date date,
                p.name name,
                (oi.delivered_quantity - oi.invoiced_quantity)quantity,
                p.parcel_size psize,
                oi.price price,
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

    $order = $result->fetchAll(PDO::FETCH_ASSOC);

class PDF extends FPDF
{

function Header()
{
    // header
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb} - Op alle diensten zijn onze algemene voorwaarden van toepassing. Deze kunt u downloaden van onze website',0,0,'C');
}
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
// Set image for the first page
$pdf->Image('img/logo.png',115,6,45);

// Basic information about the customer
$pdf->SetFont('Arial', '', 13.5);
$pdf->SetXY(15, 40);
$pdf->Multicell(90,7,"\n" . $customer[0]['name'] . "\n" . $customer[0]['contact'] . "\n$customer_address[0]\n" . substr($customer_address[1], 1) . "\n ", 1); 

// Information about timaflu
$pdf->SetFont('Arial', 'B', 8.7);
$pdf->SetXY(155, 50);   
$pdf->Write(4, 'Timaflu');

$pdf->SetFont('Arial', '', 8.7);

$pdf->SetXY(155, 54);   
$pdf->Multicell(90,4, "Straatnaam + nr\nPostcode + 's-Hertogenbosch"); 

$pdf->SetXY(140, 65);
$pdf->Multicell(15,4, "KvK nr:\n\nBTW nr:\n\nBank:\nIBAN:\nBIC:\n\nTel:\nE-mail:\nWebsite", 0, 'R'); 

$pdf->SetXY(155, 65);
$pdf->Multicell(0,4, "12345678\n\nNL123456789B01\n\nABN Amro\nNL 99 ABNA 0123 4567 89\nMijn Bank BIC Code\n\n088 12 34 567\nmijn@timaflu.nl\ntimaflu.nl", 0, 'L'); 

// Information about the factuur
$pdf->SetXY(15, 100);
$pdf->SetFont('Arial', 'B', 25);
$pdf->Write(4, "Factuur");

$pdf->SetFont('Arial', '', 12.5);
$pdf->SetXY(15, 110);
$pdf->Multicell(0,7, "Factuurnummer:\nFactuurdatum:\nUw referentie:\nVervaldatum:", 0, 'L'); 

$pdf->SetXY(50, 110);
$pdf->Multicell(0,7, "26346672\n4-1-2018\n8793645/1\n18-1-2018", 0, 'L'); 

$pdf->Line(15, 140, $pdf->GetPageWidth() - 15, 140);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0,191,255);
$pdf->Text(18, 147, "DATUM");
$pdf->Text(45, 147, "OMSCHRIJVING");
$pdf->Text(125, 147, "AANTAL");
$pdf->Text(145, 147, "STUKSPRIJS");
$pdf->Text(175, 147, "TOTAAL");
$pdf->Line(15, 150, $pdf->GetPageWidth() - 15, 150);

$pdf->SetFont('Arial', '', 11.5);
$pdf->SetTextColor(0,0,0);

// Format order info
$dates         = "";
$description   = "";
$quantity      = "";
$price         = "";
$total         = "";
$total_overall = 0;

for ($i=0; $i < sizeof($order); $i++) {
    $total_overall += intval($order[$i]['total']);

    $date = new DateTime($order[$i]['date']) ;  
    $date = $date->format('Y-m-d');
    $dates .= "\n" . $date;

    $description .= "\n" . $order[$i]['name'] . " (". $order[$i]['psize'] . ")";

    $quantity    .= "\n" . $order[$i]['quantity'];

    $price       .= "\n" . $order[$i]['price'];

    $total       .= "\n" . $order[$i]['total'];
}

// Date
$pdf->SetXY(17, 145);
$pdf->Multicell(27,8, "$dates", 0, 'L'); 

// Description
$pdf->SetXY(44, 145);
$pdf->Multicell(80,8, "$description", 0, 'L'); 

// Quantity
$pdf->SetXY(123, 145);
$pdf->Multicell(20,8, "$quantity", 0, 'R'); 

// Price
$pdf->SetXY(143, 145);
$pdf->Multicell(30,8, "$price", 0, 'R'); 

// TotalPrice
$pdf->SetXY(173, 145);
$pdf->Multicell(20,8, "$total", 0, 'R');

$pdf->Ln(5);


//Billing part
if($pdf->GetY() > 190){
    $pdf->AddPage();
}
$pdf->Line(15, 195, $pdf->GetPageWidth() - 15, 195);
$pdf->Text(16, 201, "TOTAAL EXCL. KORTNG");
$pdf->SetXY(160, 200);
$pdf->Multicell(30,0, chr(128)  . " $total_overall", 0, 'R'); 

$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(100, 210);
$pdf->Multicell(30,4, "KORTINGS%\n\n5%\n\n10%\n\n15%", 0, 'R'); 
$pdf->SetXY(130, 210);
$pdf->Multicell(30,4, "OVER\n\n-\n\n" . chr(128)  . " $total_overall\n\n-", 0, 'R'); 

$pdf->SetFont('Arial', '', 12);
$pdf->Text(16, 245, "TOTAAL KORTINGSBEDRAG");
$pdf->SetXY(160, 245);
$pdf->Multicell(50,0, "- " . chr(128)  . " 103,67 (WIP)", 0, 'R'); 

$pdf->Line(15, 248, $pdf->GetPageWidth() - 15, 248);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Text(140, 253, "TOTAAL");

$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(138, 252);
$pdf->Multicell(50,0, "" . chr(128)  . " $total_overall (WIP)", 0, 'R'); 
$pdf->Line(15, 255, $pdf->GetPageWidth() - 15, 255);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Text(145, 263, "Opmerkingen & Voorwaarden");
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(0, 265);
$pdf->Multicell(195,5, "Wij verzoeken u vriendelijk het verschuldigde bedrag binnen 14 dagen over te maken onder vermelding van het factuurnummer", 0, 'R'); 
$pdf->Output();
$pdf->Output("pdf/order_" . $order_id . ".pdf",'F');
?>