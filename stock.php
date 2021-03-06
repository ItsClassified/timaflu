<!DOCTYPE html>
<?php
require('php/functions.php');
?>
<html lang="en">
    <head>
        <title>Timaflu - Stock</title>
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
    <script type="text/javascript">
        var currentStart = 0; // Needed for the pages

        /**
            Functions for getting/editing and saving stock information
         */
        function ShowStockInfo(el) {
            var search = $('#stock_search').val();
            // var show_0 = $('#show_0_stock').is(":checked")

            $.ajax({
                type: 'post', // Type = post
                data: {stockinfo: ' ', start: currentStart, end: currentStart+10, search: search}, // Given variable
                url: "/php/ajax.php", // Link to your ajax file
                success: function(result){
                    $('#stockinfo').html(result);
                    google.charts.setOnLoadCallback(drawStockInfo(id));
                    GetPages();
            }});
        };
        function ShowProductInfo(el) {
            var id = $(el).attr('id'); 
            
            $.ajax({
                type: 'post', // Type = post
                data: {product_id: id}, // Given variable
                url: "/php/ajax.php", // Link to your ajax file
                success: function(result){
                    $('#productinfo').html(result);
            }});
        };
        function EditProductInfo(el) {
            var id = $(el).attr('value'); 

            $('.stock').each(function(){
                var stock = $(this).attr('value');

                $(this).html("<input type='text' id='stock' value='"+ stock + "'></input>"); 
            });

            $('#edit').replaceWith("<label class='message correct' OnClick='SaveProductInfo(this)' id='" + id + "'>Save</label>");
        };
        function SaveProductInfo(el) {
            var id = $(el).attr('id'); 
            
            $('.stock').each(function(){
                var stock = $(this).find('input').val();
                var date = $(this).attr('id');

                $.ajax({
                    type: 'post', // Type = post
                    data: {save_productinfo: id, date: date, stock: stock}, // Given variable
                    url: "/php/ajax.php", // Link to your ajax file
                    success: function(result){
                        $('#productinfo').html(result);
                }});
            });
        };
        /**
            Code needed for the switch between pages
         */
        function Next() {
            var start = currentStart + 10;
            var search = $('#stock_search').val();

            $.ajax({
                type: 'post', // Type = post
                data: {stockinfo: ' ', start: start, end: start + 10}, search: search, // Given variable
                url: "/php/ajax.php", // Link to your ajax file
                success: function(result){
                    $('#stockinfo').html(result);
                    currentStart = start;
                    GetPages();
            }});
        };

        function Previous() {
            if (!currentStart == 0) {

                var start = currentStart - 10;
                var search = $('#stock_search').val();

                $.ajax({
                    type: 'post', // Type = post
                    data: {stockinfo: ' ', start: start, end: start + 10, search: search}, // Given variable
                    url: "/php/ajax.php", // Link to your ajax file
                    success: function(result){
                        $('#stockinfo').html(result);
                        currentStart = start;
                        GetPages();
                }});
            }
        };

        function GetPages(){
            $.ajax({
                type: 'post', // Type = post
                data: {stockpages: currentStart}, // Given variable
                url: "/php/ajax.php", // Link to your ajax file
                success: function(result){
                    $('#pages').html(result);
            }});
        }

        /**
            Load stuff on page load
         */
        $(document).ready(function() {
            $.ajax({
                type: 'post', // Type = post
                data: {stockinfo: ' ', start: '0', end: '10', search: ' '}, // Given variable
                url: "/php/ajax.php", // Link to your ajax file
                success: function(result){
                    $('#stockinfo').html(result);
                    GetPages();
            }});

            // WHen somebodyy is searching :)
            $('#stock_search').keyup(function(e) {
                ShowStockInfo(e);
            });
        });
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
                                <h4 class="title">Stock Display</h4>
                                <p class="description">Click on a product to get more information</p>
                                <div class="row">
                                    <div class="cont12 card right content">
                                        <input id='stock_search' class='cont12' type='text'></input>
                                    </div>
                                </div>
                            </header>
                            <div id='productinfo'></div>
                            <div id='productcharts' hidden>
                                <div class='row'>
                                    <div class='card cont12'>
                                        <header>
                                            <h4 class='title'>Stock information</h4>
                                            <p class='description'>Showing a graph with the stock information regarding the currently selected product</p>
                                        </header>
                                        <div id='stockchart'></div>
                                    </div>
                                </div>
                            </div>
                            <div id='stockinfo'><?php GetStockInfo(0, 0, 10, '', true); ?></div>
                            <footer>
                                <label OnClick='Previous()'>Previous</label>
                                <label id='pages'>
                                    <?php
                                        $items = GetAmountOfPages("SELECT * FROM stock WHERE current = 1");
                                        
                                        for ($i=0; $i < $items / 10; $i++) {
                                            $j = $i + 1;
                                            $k = $i * 10;
                                            if ($k == 0) {
                                                echo "<label id='$k' OnClick='GoToPage(this)'><b>$j</b> </label>";
                                            } else {
                                                echo "<label id='$k' OnClick='GoToPage(this)'>$j </label>";
                                            }  
                                        }
                                    ?>
                                </label>
                                <label OnClick='Next()'>Next</label>
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