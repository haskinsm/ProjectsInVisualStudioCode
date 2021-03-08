<!-- 
    Purpose of Script: Edit VAT Rate
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
    <title> Edit VAT Rate </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>
        <?php include 'UniversalMenuBar.php';
        echo '<br>';
        include 'ManagerMenuBar.php';

        include ("ServerDetail.php"); 

        $sql1 = "SELECT VAT_Rate FROM Other_Data";
        $result1 = mysqli_query($link,$sql1); 
        
        while($row1=mysqli_fetch_assoc($result1)){
            
            $vat = $row1["VAT_Rate"];

            echo "VAT is currently at $vat ";
        }
         ?>

        <form action="VATRatesNext.php" method="post" name="signupform" id="signupform">
        <br>
        <label for="newVAT">New Flat Delivery Charge (Decimal):</label>
        <input type="number" name="newVAT" id="newVAT" step=".01">
        <h4><b>Enter information and click</b></h4>
            <p><input type="submit" value="Submit">
            or
            <input type="reset" value="Reset"></p>
        </form>
    </body> 
</html>
