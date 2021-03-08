<!-- 
    Purpose of Script: Change quantity of items
    Written by: Jason Yang
    last updated: Jason 2/3/21
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
    <title> Change Quantity </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>

        <?php include 'UniversalMenuBar.php';
        echo '<br>';
        include 'ManagerMenuBar.php';

        include ("ServerDetail.php"); 
        
        //Set variables from previous page
        $newQty = $_POST["newQty"];   
        $productID = $_SESSION["productID"]; 
        
        //Checks to see if quantity is entered
        if(!$newQty)
        {
            echo "Please enter the new quantity.";
            exit;
        }

        //Check if session variable gotten correctly
        if(!$productID)
        {
            echo "Error with session variable.";
            exit;
        }

        //Insert all values into SQL database
        $q  = "UPDATE Products SET Quantity = $newQty WHERE Product_ID = $productID";

        $result = $link->query($q);   
        
        ?>
        <h2>Quantity changed successfully!</h2>
    </body> 
</html>
