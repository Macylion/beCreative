<?php
    session_start();
    if(!isset($_SESSION['isLoged'])){
    ob_start();
        header('Location: index.php');
        die();
    }
    require("conn.php");
    require_once("lib.php");
    require_once("menu.php");
?>
<!DOCTYPE HTML>
<html lang="pl" class="no-js">
<head>
	<meta charset="utf8">
	<meta name="description" content="Bądź kreatyny stwórz lepszy świat.">
	<meta name="author" content="Maciej Kubus">
	<meta name="keywords" content="kreatywność, creativity, lepszy, świat">
	<title>Maciej Kubus | beCreative! | Bądź kreatyny stwórz lepszy świat</title>
	<link rel="stylesheet" href="css/about.css">
    <link rel="stylesheet" href="css/menu.css">
	<link rel="shortcut icon" href="pics/favicon.png">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.2/TweenMax.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/ScrollMagic.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/animation.gsap.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/debug.addIndicators.min.js"></script>
	<script type="text/javascript" src="js/about.js"></script>
</head>

<body>
    <?php printMenu($_SESSION['yourID'])?>

    <div id="intro">
        <div class="content">
            <img src="pics/favicon.png">
            <h1>beCreative!</h1>

        </div>
    </div>
    <section class="services-types services-types-web">
        <div class="item is-business">
            <div class="pin-wrapper">
                <div class="image"></div>
                <div class="title">
                    by&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    Maciej&nbsp;Kubus
                </div>
            </div>
        </div>
        <div class="item is-consumer">
            <div class="pin-wrapper">
                <div class="image"></div>
                <div class="list">
                	<h2>Stroneczka Wykorzystuje: </h2>
                    <ul>
                        <li>HTML & CSS</li>
                        <li>JavaScript & jQuery</li>
                        <li>PHP & MySQL</li>
                        <li>Cenny czas twórcy</li>
                        <li>Cenniejszy czas beta-testerów</li>
                        <li>Mój biedny dysko :( </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="item is-consumers">
            <div class="pin-wrapper">
                <div class="image"></div>
                <div class="titles">
                    <h2>Maciej Kubus</h2>
                    <h2>Administrator</h2>
                    <h2>I autor stronki</h2>
                    <h2><a target="_blank" href="regulamin.php">regulamin</a></h2>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
