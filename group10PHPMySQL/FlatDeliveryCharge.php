<!-- 
    Purpose of Script: Edit flat delivery charge
    Written by: Jason Yang
    last updated: 4/3/21
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
    <title> Edit Flat Delivery Charge </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>
        <?php include 'UniversalMenuBar.php';
        echo '<br>';
        include 'ManagerMenuBar.php';

        include ("ServerDetail.php"); 

        $sql1 = "SELECT Flat_Delivery_Fee FROM Other_Data";
        $result1 = mysqli_query($link,$sql1); 
        
        while($row1=mysqli_fetch_assoc($result1)){
            
            $flatDelivery = $row1["Flat_Delivery_Fee"];

            echo "Deliveries in the Dublin area currently cost €$flatDelivery plus VAT";
        }
         ?>

        <form action="FlatDeliveryNext.php" method="post" name="signupform" id="signupform">
        <br>
        <label for="newCharge">New Flat Delivery Charge:</label>
        <input type="number" name="newCharge" id="newCharge" step=".01">
        <h4><b>Enter information and click</b></h4>
            <p><input type="submit" value="Submit">
            or
            <input type="reset" value="Reset"></p>
        </form>
    </body> 
</html>
