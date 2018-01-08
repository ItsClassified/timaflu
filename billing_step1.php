<!DOCTYPE html>
<?php
require('php/functions.php');
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
        currentStart = 0;

        $('#order_search_customer_name').keyup(function(e) {
            ShowOrders(e);
        });
        $('#order_search_order_id').keyup(function(e) {
            ShowOrders(e);
        });
        
        $.ajax({
            type: 'post',
            data: {getorders: ' ', name: '', id: ''},
            url: "/php/ajax.php", 
            success: function(result){
                $('#orders').html(result);
        }});

        /**
            Functions for showing the orders
        */
        function ShowOrders(el) {
            var name = $('#order_search_customer_name').val();
            var id = $('#order_search_order_id').val();
        
            $.ajax({
                type: 'post',
                data: {getorders: ' ', name: name, id: id},
                url: "/php/ajax.php", 
                success: function(result){
                    $('#orders').html(result);
            }});
        };

        function SelectOrder(el) {
            var id = $(el).attr('id');
        
            $.ajax({
                type: 'post',
                data: {selectorder: ' ', id: id},
                url: "/php/ajax.php", 
                success: function(result){
                    window.location.href = "billing_stepA1.php";
            }});
        };

        /**
            Code needed for the switch between pages
         */
        function Next() {
            var start = currentStart + 10;
            var name = $('#order_search_customer_name').val();
            var id = $('#order_search_order_id').val();

            $.ajax({
                type: 'post',
                data: {getorders: ' ', start: start, end: start + 10, name: name, id: id},
                url: "/php/ajax.php",
                success: function(result){
                    $('#orders').html(result);
                    currentStart = start;
            }});
        };

        function Previous() {
            if (!currentStart == 0) {
                var start = currentStart - 10;
                var name = $('#order_search_customer_name').val();
                var id = $('#order_search_order_id').val();

                $.ajax({
                    type: 'post', 
                    data: {getorders: ' ', start: start, end: start + 10, name: name, id: id},
                    url: "/php/ajax.php",
                    success: function(result){
                        $('#orders').html(result);
                        currentStart = start;
                }});
            }
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
                        <a href="index.html">
                            <li>
                                <div class="notify"><div class="blinker green"></div></div>
                                <img src="img/dashboard-white.png">
                                <span class="description">Dash&shy;board</span>
                            </li>
                        </a>
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
                        <a href="billing.php">
                            <li>
                                <div class="notify"><div class="blinker orange"></div></div>
                                <img src="img/invoice.png">
                                <span class="description">Billing</span>
                            </li>
                        </a>
                        <a href="distribution.php">
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
                        <div class="cont9 card">
                            <header>
                                <h4 class="title">Order Display</h4>
                                <p class="description">Select an order to create a new invoice</p>
                                <div class="row">
                                    <div class="cont10 card right content">
                                        <input id='order_search_customer_name' class='cont12' type='text'></input>
                                    </div>
                                    <div class="cont2 card right content">
                                        <input id='order_search_order_id' class='cont12' type='text'></input>
                                    </div>
                                    
                                </div>
                            </header>
                            <div id="orders"></div>
                        </div>
                        <div class="cont3 card">
                            <header>
                                <h4 class="title">Outstanding amounts</h4>
                                <p class="description">Select a costumer to view invoices</p>
                                <div class="row">
                                    <div class="cont12 card right content">
                                        <input id='stock_search' class='cont12' type='text'></input>
                                    </div>
                                </div>
                            </header>
                            <table class="stats sortable">
                                <col style="width:57%">
                                <col style="width:43%">
                                <thead>
                                    <tr>
                                        <th>Costumer name</th>
                                        <th>Awaiting</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Alphega Apotheek De Betuwe</td>
                                        <td><div class="switcher red"><span class="blinker">&#8364; 723,83</span></div></td>
                                    </tr>
                                    <tr>
                                        <td>SAL Apotheek Brandevoort</td>
                                        <td><div class="switcher red"><span class="blinker">&#8364; 2734,22</span></div></td>
                                    </tr>
                                    <tr OnClick="document.location='billing_stepB1.html'" style="cursor:pointer">
                                        <td>BENU Apotheek Ghen heij</td>
                                        <td><div class="switcher red"><span class="blinker">&#8364; 6743,90</span></div></td>
                                    </tr>
                                </tbody>
                            </table>
                            <footer>
                                <label>Previous</label>
                                <label><b>1</b></label>
                                <label>Next</label>
                            </footer>
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