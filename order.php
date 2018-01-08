<!DOCTYPE html>
<?php
require('php/functions.php');


// TODO: Add stage clases to my divs so I can hide and show them easily isntead of doing it for every div itself.
// TODO: Add the SESSION variable, looking for someting like Array=>'Ritalin'=>2, 'Ritalin XL'=>1 , something with keys
// TODO: Add javascript to add the items to the array when they are being pressed
// TODO: Add the charts for the customer
?>
<html lang="en">
    <head>
        <title>Timaflu - Orders</title>
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css"/>
        <link rel="stylesheet" type="text/css" href="css/top.css"/>
        <link rel="stylesheet" type="text/css" href="css/form.css" />
        <link rel="stylesheet" href="css/animate.css">
        <link rel="stylesheet" href="css/message.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="js/main.js"></script>
        <script src="js/charts.js"></script>
    <script type="text/javascript">
        var currentStart = 0;
        var currentStartOrder = 0;

        /**
            Load stuff on page load 
         */
        $(document).ready(function() {
            $('#customer_search_name').keyup(function(e) {
                ShowCustomers(e);
            });
            $('#customer_search_phone').keyup(function(e) {
                ShowCustomers(e);
            });
            $('#product_search_name').keyup(function(e) {
                ShowProducts(e);
            });
            $('#product_search_id').keyup(function(e) {
                ShowProducts(e);
            });
            $('#product_search_ingredient').keyup(function(e) {
                ShowProducts(e);
            });


            var customer = <?php if(isset($_SESSION['customer'])){ echo json_encode($_SESSION['customer']); } else { echo 'false'; } ?>;
            if(customer) {
                $.ajax({
                type: 'post',
                data: {selectcustomer: customer},
                url: "/php/ajax.php",
                success: function(result){
                    $('#customerinfo').html(result);
                    $('#customerinfo').show();
                    $('#customers').hide();
                    $('#customer_search').hide();
                    $('#select').replaceWith("<label class='message error' OnClick='RemoveCustomer(this)' value='" + customer + " id='remove'>Remove</label>");
                    $('#orderinfo').show();
                    $('#product_search').show();
                    GetOrderInfo();
                }});
            }
        });

        /**
            Functions for selecting customer for our order.
         */
        function ShowCustomers(el) {
            var name = $('#customer_search_name').val();
            var phone = $('#customer_search_phone').val();
        
            $.ajax({
                type: 'post',
                data: {getcustomers: ' ', name: name, phone: phone},
                url: "/php/ajax.php", 
                success: function(result){
                    $('#customers').html(result);
                    $('#customers').show();
            }});
        };
        function SelectCustomer(el) {
            var id = $(el).attr('id'); 
            
            $.ajax({
                type: 'post',
                data: {selectcustomer: id},
                url: "/php/ajax.php",
                success: function(result){
                    $('#customerinfo').html(result);
                    $('#customerinfo').show();
                    $('#customers').hide();
            }});
        };

        function ConfirmCustomer(el) {
            var id = $(el).attr('value'); 
            
            $.ajax({
                type: 'post',
                data: {confirmcustomer: id},
                url: "/php/ajax.php",
                success: function(result){
                    $('#customers').hide();
                    $('#customer_search').hide();
                    $('#select').replaceWith("<label class='message error' OnClick='RemoveCustomer(this)' value='" + id + " id='remove'>Remove</label>");
                    $('#orderinfo').show();
                    $('#product_search').show();
                    GetOrderInfo();
            }});
        };

        function RemoveCustomer(el) {
            var id = $(el).attr('value'); 
            
            $.ajax({
                type: 'post', 
                data: {removecustomer: id},
                url: "/php/ajax.php",
                success: function(result){
                    $('#customerinfo').hide();
                    $('#orderinfo').hide();
                    $('#customer_search').show();
                    $('#product_search').hide();
            }});
        };

        /**
            Functions for our order information
         */
        function GetOrderInfo() {        
            $.ajax({
                type: 'post',
                data: {getorderinfo: ' ', start: currentStartOrder},
                url: "/php/ajax.php",
                success: function(result){
                    $('#orderinfo').html(result);
            }});
        };

        /**
            Functions for the products
         */
        function ShowProducts(el) {
            var name = $('#product_search_name').val();
            var id = $('#product_search_id').val();
            var ingredient = $('#product_search_ingredient').val();
        
            $.ajax({
                type: 'post',
                data: {getproducts: ' ', start: currentStart, end: currentStart+10, name: name, id: id, ingredient: ingredient},
                url: "/php/ajax.php",
                success: function(result){
                    $('#products').html(result);
                    $('#products').show();
            }});
        };

        function SelectProduct(el) {
            var id = $(el).attr('value'); 
        
            $.ajax({
                type: 'post',
                data: {selectproduct: id},
                url: "/php/ajax.php",
                success: function(result){
                    $('#productinfo').html(result);
                    $('#productinfo').show();
                    $('#products').hide();
            }});
        };

        function ConfirmProduct(el) {
            var id = $(el).attr('value'); 
            var amount = $('#product_amount').val();
            var price = $('#product_price').attr('value'); // TODO FIX SO IT DOESNT GETS THE PRICE OF ANOTHER ITEM

            $.ajax({
                type: 'post',
                data: {confirmproduct: id, amount: amount, price: price},
                url: "/php/ajax.php",
                success: function(result){
                    GetOrderInfo();
                    $('#productinfo').hide();
                    $('#products').show();
            }});
        };

        /**
            Code needed for the switch between pages
         */
        function Next() {
            var start = currentStart + 10;
            var name = $('#product_search_name').val();
            var id = $('#product_search_name').val();

            $.ajax({
                type: 'post',
                data: {getproducts: ' ', start: start, end: start + 10, name: name, id: id},
                url: "/php/ajax.php",
                success: function(result){
                    $('#products').html(result);
                    currentStart = start;
            }});
        };

        function Previous() {
            if (!currentStart == 0) {
                var start = currentStart - 10;
                var name = $('#product_search_name').val();
                var id = $('#product_search_name').val();

                $.ajax({
                    type: 'post', 
                    data: {getproducts: ' ', start: start, end: start + 10, name: name, id: id},
                    url: "/php/ajax.php",
                    success: function(result){
                        $('#products').html(result);
                        currentStart = start;
                }});
            }
        };

        function NextOrder() {
            var start = currentStartOrder + 10;

            $.ajax({
                type: 'post',
                data: {getorderinfo: ' ', start: start, end: start + 10},
                url: "/php/ajax.php",
                success: function(result){
                    $('#orderinfo').html(result);
                    currentStartOrder = start;
            }});
        };

        function PreviousOrder() {
            if (!currentStartOrder == 0) {
                var start = currentStartOrder - 10;

                $.ajax({
                    type: 'post', 
                    data: {getorderinfo: ' ', start: start, end: start + 10},
                    url: "/php/ajax.php",
                    success: function(result){
                        $('#orderinfo').html(result);
                        currentStartOrder = start;
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
                    <div class="dropdown">
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
                        <div class="cont12 card">
                            <header>
                                <h4 class="title">Create Order</h4>
                                <p class="description">Search for a customer or create a new one</p>
                                <div class="row" id="customer_search">
                                    <div class="cont7 card">
                                        <input id='customer_search_name' class="cont12" type='text' placeholder="Customer Name"></input>
                                    </div>
                                    <div class="cont3 card">
                                        <input id='customer_search_phone' class="cont12" type='text' placeholder="Phone Number"></input>
                                    </div>
                                    <div class="cont2">
                                        <div class="message" >Create New</div> <!-- TODO FIX THIS buLLSHIT SO IT LOOKS BETTER -->
                                    </div>
                                </div>
                            </header>
                            <div id='customers'></div>
                            <div id='customerinfo'></div>
                            <div id='newcustomer' hidden>
                                <div class='card'>
                                    <table class='stats'>
                                        <tr>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Contact</th>
                                            <th>URL</th>
                                        </tr>
                                        <tr>
                                            <td><input id='customer_name' class="cont12" type='text' placeholder="Name"></input></td>
                                            <td><input id='phone' class="cont12" type='text' placeholder="Phone"></input></td>
                                            <td><input id='contact_name' class="cont12" type='text' placeholder="Contact name"></input></td>
                                            <td><input id='url' class="cont12" type='text' placeholder="URL"></input></td>            
                                        </tr>
                                    </table>
                                    <footer>
                                        <label class='message correct clickable' OnClick='ConfirmCustomer(this)' id='select' value='" . $customer_id . "'>Confirm</label>
                                        <label class='message warn clickable'>Close</label>
                                        <label OnClick='RemoveCustomer(this)' id='" . $customer_id . "' class='message correct clickable'>Charts</label>
                                    </footer>
                                </div>
                            </div>
                            <div id='customercharts' hidden></div>
                            <div id='orderinfo' style="display: none;"></div>
                            <div class="row" id="product_search" style="display: none;">
                                <div class="cont10 card">
                                    <input id='product_search_name' class="cont12" type='text' placeholder="Product Name"></input>
                                </div>
                                <div class="cont1 card">
                                    <input id='product_search_ingredient' class="cont12" type='text' placeholder="Active Ingredient"></input>
                                </div>
                                <div class="cont1 card">
                                    <input id='product_search_id' class="cont12" type='text' placeholder="Product ID"></input>
                                </div>
                            </div>
                            <div id='productinfo' style="display: none;"></div>
                            <div id='products' style="display: none;"></div>
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