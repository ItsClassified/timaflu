<!DOCTYPE html> <!-- CLEANED -->
<?php
require('php/functions.php');

if(!isset($_SESSION['billing_order'])){
    header("Location: billing_step1.php");
}

?>
<html lang="en">
    <head>
        <title>Timaflu - Billing</title>
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css"/>
        <link rel="stylesheet" type="text/css" href="css/top.css"/>
        <link rel="stylesheet" type="text/css" href="css/form.css" />
        <link rel="stylesheet" href="css/animate.css">
        <link rel="stylesheet" href="css/message.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <!-- <link href="css/stylesheet.css" rel="stylesheet" type="text/css"> -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="js/main.js"></script>
        <script src="js/charts.js"></script>
        <script src="js/sorttable.js"></script>

        <script type="text/javascript">

            function SendInvoice(el) {
                var id = $(el).attr('id');
            
                $.ajax({
                    type: 'post',
                    data: {send_invoice: ' ', id: id},
                    url: "/php/ajax.php",
                    success: function(result){
                        alert("Invoices has been send trough e-mail! :)");
                }});
            };
        </script>
    </head>
    <body>
        <div id="main">
            <header class="top">
                <div class="logo">TIMAFLU</div>
                <div class="title">January 4th, 2018</div>
                <ul class="menu">
                    <div class="dropdown"><!-- Needed for dropdown-content, so when hovering over it it keeps displaying -->
                        <a href="index.html">
                            <li><div class="profile-picture"></div><span>dr. B. Onderstal</span></li>
                        </a>
                        <ul class="dropdown-content">
                            <a href="index.html"><li><img src="img/logout.png"><span>Logout</span></li></a>
                            <a href="index.html"><li><img src="img/settings.png"><span>Settings</span></li></a>
                        </ul>
                    </div>
                </ul>
            </header>
            <div class="container-main"> 
                <header class="hero">
                    <div class="step">
                        
                    </div>
                </header>
                <div class="main">
                    <div class="row">
                        <div class="cont5 card">
                            <header>
                                <h4 class="title">Creating invoice for order <b><?php echo $_SESSION['billing_order']; ?></b></h4>
                                <p class="description">Billable items are being invoiced</p>
                            </header>
                            <?php echo GetInvoiceItems($_SESSION['billing_order']); ?>
                            <div class="message s12" id="<?php echo $_SESSION['billing_order']; ?>" OnClick='SendInvoice(this)'>&#x1F4BE; Create &amp; Send invoice</div> <br>
                            <footer>Notice: selected items will be marked as invoiced.</footer>
                        </div>
                        <div class="cont7 card">
                            <header>
                                <h4 class="title">Invoice &#8470; <b>1</b> for order <b><?php echo $_SESSION['billing_order']; ?></b></h4>
                                <p class="description">Generated invoice</p>
                            </header>
                            <object data="make_invoice_pdf.php" type="application/pdf" width="100%" height="1000px">
                            </object>
                        </div>
                    </div>
                    <footer>
                        <label>&#9400; 2017 | version 1.0 beta 6</label>
                        <label><a href="sitemap.html"><img src="img/sitemap.png" alt="Sitemap"></a>&#10095; Sitemap</label>
                        <label>
                            <a href="index.html">Home</a>
                            <span> - </span>
                            <a href="stats.html">Stats</a>
                            <span> - </span>
                            <a href="contact.html">Contact</a>
                            <span> - </span>
                            <a href="game.html">Game</a>
                            <span> - </span>
                            <a href="aboutus.html">About</a>
                        </label>
                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>