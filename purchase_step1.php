<!DOCTYPE html>
<?php
require('php/functions.php');
?>
<html lang="en">
    <head>
        <title>Timaflu - Purchasing</title>
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
        function SelectProduct(el) {
            var id = $(el).attr('id');
            var strength = $(el).attr('value');

            $.ajax({
                type: 'post',
                data: {select_product_manufacturer: ' ', id: id, strength: strength},
                url: "/php/ajax.php", 
                success: function(result){
                    window.location.href = "purchase_step2.php";
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
                    <ul class="items">           
                        <a href="order.php">
                            <li>
                                <div class="notify"><div class="blinker green"></div></div>
                                <img src="img/neworder.png">
                                <span class="description">Create Order</span>
                            </li>
                        </a>
                        <a href="stock.php">
                            <li>
                                <div class="notify"><div class="blinker red"></div></div>
                                <img src="img/stock.png">
                                <span class="description">Stock</span>
                            </li>
                        </a>
                        <a href="billing_step1.php">
                            <li>
                                <div class="notify"><div class="blinker orange"></div></div>
                                <img src="img/invoice.png">
                                <span class="description">Billing</span>
                            </li>
                        </a>
                        <a href="purchase_step1.php">
                            <li>
                                <div class="notify"><div class="blinker red"></div></div>
                                <img src="img/purchase.png">
                                <span class="description">Purchase</span>
                            </li>
                        </a>
                        <a href="distribution_step1.html">
                            <li>
                                <div class="notify"><div class="blinker red"></div></div>
                                <img src="img/distr.png">
                                <span class="description">Distribution</span>
                            </li>
                        </a>
                    </ul>
                </header>
                <div class="main">
                    <div class="row">
                        <div class="cont12 card">
                            <header>
                                <h4 class="title">Low Stock Display</h4>
                                <p class="description">Select a product to view different manufacturer prices</p>
                                <div class="row">
                                    <div class="cont12 card right content">
                                        <input id='products_search_name' class='cont12' type='text'></input>
                                    </div>
                                </div>
                            </header>
                           <?php echo StockInfoActiveIngredient('', '');?>
                           <footer>
                                <label>Previous</label>
                                <label><b>1</b></label>
                                <label>Next</label>
                            </footer>
                        </div>
                    </div>
                    <footer>
                        <label></label>
                        <label>&#9400; 2017 | version 1.0 beta 6</label>
                        <label></label>
                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>