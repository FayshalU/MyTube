<?php
    require_once("config.php");
    require_once("classes/user.php");
    require_once("classes/video.php");

    $username = User::isLoggedIn() ? $_SESSION["user"] : "";
    $user = new USER($con, $username);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MyTube</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" media="screen" href="assets/lib/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="assets/lib/css/font-awesome.css">
        <link rel="stylesheet" type="text/css" media="screen" href="assets/css/style.css">

        <script src="assets/lib/js/jquery-3.3.1.js"></script>
        <script src="assets/lib/js/popper.min.js"></script>
        <script src="assets/lib/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div id="pageContainer">
            <div id="mastHeadContainer">
                <button class="navShowHide">
                    <i class="fa fa-bars fa-lg" aria-hidden="true"></i>
                </button>

                <a class="logoContainer" href="index.php">
                    <img src="assets/images/yt.png" alt="MyTube" title="Logo">
                </a>

                <div class="searchBarContainer">
                    <form action="search.php" method="GET">
                        <input type="text" class="searchBar" name="searchData" placeholder="Search">
                        <button class="searchBtn">
                            <i class="fa fa-search fa-lg" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>

                <div class="rightButtons">
                    <a href="upload.php">
                        <i class="fa fa-upload fa-lg" aria-hidden="true"></i>
                    </a>
                    <a href="#">
                        <i class="fa fa-user-circle-o fa-lg" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <div id="sideNavContainer" style="display:none;">

            </div>

            <div id="mainSectionContainer">
                <div id="mainContentContainer">