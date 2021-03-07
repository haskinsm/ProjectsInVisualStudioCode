<!-- 
    Purpose of Script: Allow them to pick whether they want to alter the roster for DPH delivered and collected bookings or Customer picked up and returned bookings
    Written by: Michael 
    last updated: 07/03/21
       Witten
-->

<?php
    // Start the session
    session_start();


     if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["Position"]) && $_SESSION["Position"] === Manager)){
    	header("location: ManagerLogin.php");
    	exit;
     }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Employee Roster Alterations </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
    
    <!--Reference: https://www.w3schools.com/css/tryit.asp?filename=trycss_buttons_animate1-->
    <style>
        .button {
        display: inline-block;
        border-radius: 4px;
        background-color: #30D5C8;
        border: none;
        color: #f2f2f2;
        text-align: center;
        font-size: 16px;
        padding: 10px;
        width: 500px;
        transition: all 0.5s;
        cursor: pointer;
        margin: 5px;
        }

        .button span {
        cursor: pointer;
        display: inline-block;
        position: relative;
        transition: 0.5s;
        }

        .button span:after {
        content: '\00bb';
        position: absolute;
        opacity: 0;
        top: 0;
        right: -20px;
        transition: 0.5s;
        }

        .button:hover span {
        padding-right: 25px;
        }

        .button:hover span:after {
        opacity: 1;
        right: 0;
        }
    </style>
</head>
<body>

    <?php include 'UniversalMenuBar.php';
    echo '<br>';
    include 'ManagerMenuBar.php';?> <!-- Imports code for manager menu bar from another php file-->

    <h2>Please select one of the following options:</h2>
    <button class="button" style="vertical-align:middle" onclick="window.location.href='EmployeeRostering.php'"> Make Roster alterations for Bookings Delivered & Collected by DPH </button>
    <br>
    <button class="button" style="vertical-align:middle" onclick="window.location.href='EmployeePickUpReturnRostering.php'"> Make Roster alterations for Bookings Pickedup & Returned by Customers </button>
    <br>
</body>
</html>