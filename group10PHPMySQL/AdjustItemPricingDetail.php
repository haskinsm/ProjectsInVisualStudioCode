<!-- 
    Purpose of Script: Change price of items
    Written by: Jason Yang
    last updated: Jason 3/3/21, 4/3/21, 5/3/21
    Add functionality for discounts
    Add discount check; discount cannot be more than rental price
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
    <title> Change Pricing </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>

        <?php include 'UniversalMenuBar.php';
        echo '<br>';
        include 'ManagerMenuBar.php';

        include ("ServerDetail.php"); 
        
        //Set variables from previous page
        $newRent = $_POST["newRent"];  
        $newSetup = $_POST["newSetup"];  
        $newDiscount = $_POST["newDiscount"];  
        $productID = $_SESSION["productID"]; 
        
        //Checks to see if rent is entered
        if(!$newRent)
        {
            echo "Please enter the new rent costs.";
            exit;
        }

        //Checks to see if setup cost is entered
        if(!$newSetup)
        {
            echo "Please enter the new setup costs.";
            exit;
        }

        //Checks to see if discount cost is entered
        if(!$newDiscount)
        {
            echo "Please enter the new discount costs.";
            exit;
        }

        //Check if discount is more than actual cost
        if($newDiscount > $newRent)
        {
            echo "Discount cannot be more than rental price!";
            exit;
        }

        //Check if session variable gotten correctly
        if(!$productID)
        {
            echo "Error with session variable.";
            exit;
        }

        //Insert all values into SQL database
        $q  = "UPDATE Products SET Rental_Fee = $newRent , Setup_Cost = $newSetup, Euro_Discount = $newDiscount WHERE Product_ID = $productID";

        $result = $link->query($q);   
        
        ?>
        <h2>Prices changed successfully!</h2>
    </body> 
</html>
