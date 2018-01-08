<!DOCTYPE html>

<?php 
require('php/functions.php');
?>
<html lang="en">
    <head>
        <title>Gatherstuff - Home</title>
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css"/>
        <link rel="stylesheet" type="text/css" href="css/top.css"/>
        <link rel="stylesheet" type="text/css" href="css/form.css" />
        <link rel="stylesheet" href="css/animate.css">
        <link rel="stylesheet" href="css/message.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <!-- <link href="css/stylesheet.css" rel="stylesheet" type="text/css"> -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="js/main.js"></script>
    </head>
    <body>
        <div id="main">
            <header class="top">
                <div class="logo"><span>GATHER STUFF</span></div>
                <div class="navcontainer">
                    <!-- nav is in a navcontainer so it can be moved easily when using @media queries -->
                    <nav>
                        <ul class="back">
                            <a href="" title="Scroll to top">
                                <li id="1">
                                    <img src="img/arrow-up.png">
                                </li>
                            </a>
                            <a href="index.html" title="Dashboard">
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
                        <a href="game.html">
                            <li>
                                <img src="img/profile-white.png">
                                <span class="description">Your Game</span>
                            </li>
                        </a>
                        <a href="stats.html">
                            <li>
                                <img src="img/stats-white.png">
                                <span class="description">Your Stats</span>
                            </li>
                        </a>
                        <a href="leaderboards.html">
                            <li>
                                <img src="img/leaderboards-white.png">
                                <span class="description">Leader&shy;boards</span>
                            </li>
                        </a>
                        <a href="creatematch.html">
                            <li>
                                <img src="img/dashboard-white.png">
                                <span class="description">Create Match</span>
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
                        <div class="cont4 card">
                            <header>
                                <h4 class="title">Most kills</h4>
                                <p class="description">Top 5 players with the most kills</p>
                            </header>
                            <table class="stats">
                                <?php $orderinfo = GetOrderInfo(2);
                                
                                var_dump($orderinfo); ?>
                            </table>
                        </div> 
                        <div class="cont4 card">
                            <header>
                                <h4 class="title">Most kills</h4>
                                <p class="description">Top 5 players with the most kills</p>
                            </header>
                            <table class="stats">
                                <tr>
                                    <td><a href="#">Classified</a></td>
                                    <td>9999</td>
                                </tr>
                                <tr>
                                    <td><a href="#">Martijn</a></td>
                                    <td>99</td>
                                </tr>
                                <tr>
                                    <td><a href="#">Martijn</a></td>
                                    <td>99</td>
                                </tr>
                                <tr>
                                    <td><a href="#">Martijn</a></td>
                                    <td>99</td>
                                </tr>
                                <tr>
                                    <td><a href="#">Martijn</a></td>
                                    <td>99</td>
                                </tr>
                            </table>
                        </div>   
                        <div class="cont4 card">
                            <header>
                                <h4 class="title">Most kills</h4>
                                <p class="description">Top 5 players with the most kills</p>
                            </header>
                            <table class="stats">
                                <tr>
                                    <td><a href="#">Classified</a></td>
                                    <td>9999</td>
                                </tr>
                                <tr>
                                    <td><a href="#">Martijn</a></td>
                                    <td>99</td>
                                </tr>
                                <tr>
                                    <td><a href="#">Martijn</a></td>
                                    <td>99</td>
                                </tr>
                                <tr>
                                    <td><a href="#">Martijn</a></td>
                                    <td>99</td>
                                </tr>
                                <tr>
                                    <td><a href="#">Martijn</a></td>
                                    <td>99</td>
                                </tr>
                            </table>
                        </div>   
                    </div>
                    <footer>
                        <label>&#9400; 2017 by <a href="aboutus.html"><b>Classified</b></a> &amp; <a href="aboutus.html"><b>Yannick</b></a></label>
                        <label><a href="sitemap.html"><img src="img/sitemap.png" alt="Sitemap"></a>&#10095; Dashboard</label>
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
        <script type="text/javascript">    
            
        function changeCSS(cssFile, cssLinkIndex) {
        
            var oldlink = document.getElementsByTagName("link").item(cssLinkIndex);
        
            var newlink = document.createElement("link");
            newlink.setAttribute("rel", "stylesheet");
            newlink.setAttribute("type", "text/css");
            newlink.setAttribute("href", cssFile);
        
            document.getElementsByTagName("head").item(0).replaceChild(newlink, oldlink);
        }
        </script>
    </body>
</html>