<!-- 
    Purpose of Script: Adjust Delivery Charges & VAT Rates
    Written by: Jason Yang
    last updated: Jason 3/3/21
-->

<?php
    // Start the session
    session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Adjust Items </title>
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
    <button class="button" style="vertical-align:middle" onclick="window.location.href='FlatDeliveryCharge.php'">Edit Flat Delivery Charge</button>
    <br>
    <button class="button" style="vertical-align:middle" onclick="window.location.href='AdditionalDeliveryCharge.php'">Edit Aditional Delivery Charge</button>
    <br>
    <button class="button" style="vertical-align:middle" onclick="window.location.href='VATRates.php'">Edit VAT Rate</button>
    <br>
</body>
</html>