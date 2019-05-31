<?php
    ob_start();
    session_start();
    date_default_timezone_get("Asia/Dhaka");

    try {
        $con = new PDO("mysql:host=localhost;dbname=myTube", "root", "");
        // set the PDO error mode to exception
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        //echo "Connected successfully"; 
        }
    catch(PDOException $e)
        {
        echo "Connection failed: " . $e->getMessage();
        }
?>