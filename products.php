<!DOCTYPE html>
<?php
require('php/functions.php');
?>
<html lang="en">
    <head>
        <title>Timaflu - Products</title>
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
        var currentStart = 0; // Needed for the pages
        /**
            Load stuff on page load
         */
        $(document).ready(function() {

            $.ajax({
                type: 'post', // Type = post
                data: {maininfo: ' ', start: '0', end: '10', search: ' '}, // Given variable
                url: "/php/ajax.php", // Link to your ajax file
                success: function(result){
                    $('#maininfo').html(result);
                    GetPages();
            }});

            // WHen somebodyy is searching :)
            $('#item_search').keyup(function(e) {
                ShowMainInfo();
            });
        });

        /**
            Functions for getting/editing and saving stock information
         */
        function ShowMainInfo() {
            var search = $('#item_search').val();

            $.ajax({
                type: 'post', // Type = post
                data: {maininfo: ' ', start: currentStart, end: currentStart+10, search: search}, // Given variable
                url: "/php/ajax.php", // Link to your ajax file
                success: function(result){
                    $('#maininfo').html(result);
                    GetPages();
            }});
        };
        function ShowItemInfo(el) {
            var id = $(el).attr('id'); 
            
            $.ajax({
                type: 'post', // Type = post
                data: {iteminfo: id}, // Given variable
                url: "/php/ajax.php", // Link to your ajax file
                success: function(result){
                    $('#iteminfo').html(result);
            }});
        };

        

        /**
            Code needed for the switch between pages
         */
        function Next() {
            currentStart = currentStart + 10;
            ShowMainInfo();
        };

        function Previous() {
            if (!currentStart == 0) {
                currentStart = currentStart - 10;
                ShowMainInfo();
            }
        };

        function GetPages(){
            $.ajax({
                type: 'post', // Type = post
                data: {getpages: currentStart}, // Given variable
                url: "/php/ajax.php", // Link to your ajax file
                success: function(result){
                    $('#pages').html(result);
            }});
        }
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
                                <div class="row">
                                    <div class="cont12 card right content">
                                        <input id='item_search' class='cont2' type='text'></input>
                                    </div>
                                </div>
                            </header>
                            <div id='iteminfo'></div>                        
                            <div id='maininfo'><?php echo 'lol' ?></div>
                            <footer>
                                <label OnClick='Previous()'>Previous</label>
                                <label id='pages'>
                                    <?php
                                        $items = 100;
                                        
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