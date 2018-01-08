<!DOCTYPE html>
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
                        alert("Email has been send :)");
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
                        <progress>Unknown</progress>
                        <button type="button" formaction="billing_step1.html">&#x274C; Cancel invoice</button>
                        <button id="<?php echo $_SESSION['billing_order']; ?>" OnClick='SendInvoice(this)'>&#x1F4BE; Send &amp; save invoice</button>
                    </div>
                </header>
                <div class="main">
                    <div class="row">
                        <div class="cont5 card">
                            <header>
                                <h4 class="title">Creating invoice for order <b><?php echo $_SESSION['billing_order']; ?></b></h4>
                                <p class="description">Select items you want to add to invoice</p>
                            </header>
                            <table class="products sortable">
                                <col style="width:5%">
                                <col style="width:57%">
                                <col style="width:8%">
                                <col style="width:15%">
                                <col style="width:15%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th><span>Product</span></th>
                                        <th><span>Qt.</span></th>
                                        <th><span>Price/p</span></th>
                                        <th><span>Total</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input checked type="checkbox"></td>
                                        <td><span>Selokeen<sup>&#174;</sup> ZOC 50 (47,5 mg)</span></td>
                                        <td><span>150</span></td>
                                        <td><span>&#8364; 5,60</span></td>
                                        <td><span>&#8364; 840,00</span></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td><span>MAXALT<sup>&#174;</sup> smelt (10 mg) (grootverp. 50st.)</span></td>
                                        <td><span>5</span></td>
                                        <td><span>&#8364; 1219,33</span></td>
                                        <td><span>&#8364; 6096,65</span></td>
                                    </tr>
                                    <tr>
                                        <td><input checked type="checkbox"></td>
                                        <td><span>Medikinet CR<sup>&#174;</sup> (30 mg) (grootverp. 50st.)</span></td>
                                        <td><span>3</span></td>
                                        <td><span>&#8364; 65,55</span></td>
                                        <td><span>&#8364; 196,65</span></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button">Create invoice</button>
                            <label>Notice: selected items will be marked as invoiced.</label>
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