<?php 

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
$mime -> addAttachment('pdf/order_2.pdf', 'application/pdf', 'factuur.pdf',true,'base64', 'attachment');
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
?>