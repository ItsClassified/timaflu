<!DOCTYPE html>
<?php
require('php/functions.php');
?>
<html lang="en">
    <head>
        <title>Gatherstuff - Sitemap</title>
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css"/>
        <link rel="stylesheet" type="text/css" href="css/top.css"/>
        <link rel="stylesheet" type="text/css" href="css/form.css" />
        <link rel="stylesheet" href="css/animate.css">
        <link rel="stylesheet" href="css/message.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <!-- <link href="css/stylesheet.css" rel="stylesheet" type="text/css"> -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
        <script src="js/main.js"></script>
        
    <script type="text/javascript"> 
    var currentStart = 0;

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

        function Next() {
            var start = currentStart + 10;
            
            $.ajax({
                type: 'post', // Type = post
                data: {stockinfo: ' ', start: start, end: start + 10}, // Given variable
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
                
                $.ajax({
                    type: 'post', // Type = post
                    data: {stockinfo: ' ', start: start, end: start + 10}, // Given variable
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

        $(document).ready(function() {
            $.ajax({
                type: 'post', // Type = post
                data: {stockinfo: ' ', start: '0', end: '10'}, // Given variable
                url: "/php/ajax.php", // Link to your ajax file
                success: function(result){
                    $('#stockinfo').html(result);
                    GetPages();
            }});
        });
    </script>
         
    </head>
    <body>
        <div id="main">
            <header class="top">
                <div class="logo">GATHER STUFF</div>
                <div class="navcontainer">          
                    <nav>
                        <ul class="back">
                            <a href="" title="Scroll to top">
                                <li id="1">
                                    <img src="img/arrow-up.png">
                                </li>
                            </a>
                            <a href="index.html" title="Home">
                                <li>
                                    <img class="invert" src="img/dashboard-white.png">
                                </li>
                            </a>
                            <a href="game.html" title="Your Game">
                                <li>
                                    <img class="invert" src="img/profile-white.png">
                                </li>
                            </a>
                            <a href="stats.html" title="Statistics">
                                <li>
                                    <img class="invert" src="img/stats-white.png">
                                </li>
                            </a>
                            <a href="leaderboards.html" title="Leaderboards">
                                <li>
                                    <img class="invert" src="img/leaderboards-white.png">
                                </li>
                            </a>
                            <a href="creatematch.html" title="Create a Match">
                                <li>
                                    <img class="invert" src="img/dashboard-white.png">
                                </li>
                            </a>
                            <a href="contact.html" title="Contact">
                                <li>
                                    <img class="invert" src="img/contact-white.png">
                                </li>
                            </a>
                        </ul>
                        <ul class="front">
                            <a href="" title="Messages">
                                <li>
                                    <img src="img/mail.png">
                                </li>
                            </a>
                            <a href="" title="Alerts">
                                <li>
                                    <img src="img/alert.png">
                                </li>
                            </a>
                        </ul>
                    </nav>
                </div>                    
                <ul class="menu">
                    <div class="dropdown"><!-- Needed for dropdown-content, so when hovering over it it keeps displaying -->
                        <a href="index.html">
                            <li><div class="profile-picture"></div><span>Classified</span></li>
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
                                <img src="img/dashboard-white.png">
                                <span class="description">Dash&shy;board</span>
                            </li>
                        </a>
                        <a href="index.html">
                            <li>
                                <img src="img/dashboard-white.png">
                                <span class="description">Create Order</span>
                            </li>
                        </a>
                        <a href="index.html">
                            <li>
                                <img src="img/dashboard-white.png">
                                <span class="description">Stock</span>
                            </li>
                        </a>
                        <a href="contact.html">
                            <li>
                                <img src="img/contact-white.png">
                                <span class="description">Contact</span>
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
                            </header>
                            <div id='productinfo'></div>                        
                            <div id='stockinfo'><?php GetStockInfo(0, 0, 10); ?></div>
                            <footer>
                                <label OnClick='Previous()'>Previous</label>
                                <label id='pages'>
                                    <?php
                                        $items = GetAmountOfStockPages();
                                        
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
                        <label>&#9400; 2017 by <a href="aboutus.html"><b>Classified</b></a> &amp; <a href="aboutus.html"><b>Yannick</b></a></label>
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