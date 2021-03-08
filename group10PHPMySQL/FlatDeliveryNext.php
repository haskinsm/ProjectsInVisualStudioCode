<!-- 
    Purpose of Script: Flat Delivery Charge
    Written by: Jason Yang
    last updated: Jason 4/3/21
-->
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Flat Delivery Charge </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>

        <?php include 'UniversalMenuBar.php';
        echo '<br>';
        include 'ManagerMenuBar.php';

        include ("ServerDetail.php"); 
        
        //Set variables from previous page
        $newCharge = $_POST["newCharge"];  

        //Insert all values into SQL database
        $q  = "UPDATE Other_Data SET Flat_Delivery_Fee = $newCharge";

        $result = $link->query($q);   
        
        ?>
        <h2>Flat Delivery Charge Updated Successfully!</h2>
    </body> 
</html>
