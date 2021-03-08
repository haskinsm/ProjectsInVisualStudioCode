<!-- 
    Purpose of Script: Add new Items
    Written by: Jason Yang
    last updated: Jason 1/3/21, 5/3/21
    Add discount
-->

<?php
    // Start the session
    session_start();

     if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["Position"]) && $_SESSION["Position"] == Manager)){
    	header("location: ManagementLogin.php");
    	exit;
     }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Add Items </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>

    <?php include 'UniversalMenuBar.php';
    echo '<br>';
    include 'ManagerMenuBar.php';?> <!-- Imports code for manager menu bar from another php file-->

        <!--This is the add item form-->
        <form action="AddItemNext.php" method="post" name="signupform" id="signupform">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" id="product_name" size=20>
            <br><br>
            <label for="rental_fee">Rental Fee / 48 hours:</label>
            <input type="number" name="rental_fee" id="rental_fee" step=".01"> 
            <br><br>
            <label for="setup_cost">Setup Cost:</label>
            <input type="number" name="setup_cost" id="setup_cost" step=".01"> 
            <br><br>
            <label for="discount_rate">Discount Amount in Euros:</label>
            <input type="number" name="discount_rate" id="discount_rate" step=".01"> 
            <br><br>
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" size=11>
            <br><br>
            <h4><b>Enter information and click</b></h4>
            <p><input type="submit" value="Submit">
            or
            <input type="reset" value="Reset"></p>
        </form>
    </body> 
</html>