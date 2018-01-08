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
        var productsStart = 0;

        $('#products_search_name').keyup(function(e) {
            alert(e);
            ShowProducts(e);
        });
        // $('#products_search_id').keyup(function(e) {
        //     ShowProducts(e);
        // });
        
        $.ajax({
            type: 'post',
            data: {getlowproducts: ' ', name: '', id: ''},
            url: "/php/ajax.php", 
            success: function(result){
                $('#products').html(result);
        }});

        /**
            Functions for showing the orders
        */
        function ShowProducts(el) {
            var name = $('#products_search_name').val();
            // var id = $('#order_search_order_id').val();
        
            $.ajax({
                type: 'post',
                data: {getlowproducts: ' ', name: name},
                url: "/php/ajax.php", 
                success: function(result){
                    $('#orders').html(result);
            }});
        };

        /**
            Code needed for the switch between pages
         */
        function Next() {
            var start = productsStart + 10;
            var name = $('#products_search_name').val();

            $.ajax({
                type: 'post',
                data: {getlowproducts: ' ', start: start, end: start + 10, name: name},
                url: "/php/ajax.php",
                success: function(result){
                    $('#products').html(result);
                    productsStart = start;
            }});
        };

        function Previous() {
            if (!productsStart == 0) {
                var start = productsStart - 10;
                var name = $('#products_search_name').val();

                $.ajax({
                    type: 'post', 
                    data: {getlowproducts: ' ', start: start, end: start + 10, name: name},
                    url: "/php/ajax.php",
                    success: function(result){
                        $('#products').html(result);
                        productsStart = start;
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
                    <div class="step">
                    </div>
                </header>
                <div class="main">
                    <div class="row">
                        <div class="cont9 card">
                            <header>
                                <h4 class="title">Manufacturers for asked ingredient</h4>
                                <p class="description">...</p>
                            </header>
                            <?php GetManufacturerList($_SESSION['aiid'], $_SESSION['strength']); ?>
                        </div>
                        <div class="cont3 card">
                            <header>
                                <h4 class="title">Contact info for cheepest manufacturer</h4>
                                <p class="description">Contact information for the manufacturer with lowest price</p>
                            </header>
                            <?php GetManufacturerInfo($_SESSION['mid']); ?>
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