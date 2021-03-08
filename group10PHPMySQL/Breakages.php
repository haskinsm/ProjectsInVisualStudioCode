<!-- 
    Purpose of Script: Track breakages
    Written by: Jason Yang
    last updated: Jason 2/3/21
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
    <title> Breakages </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>

    <?php include 'UniversalMenuBar.php';
    echo '<br>';
    include 'ManagerMenuBar.php';?> 
        <form action="BreakagesNext.php" method="post" name="signupform" id="signupform">
            <label for="booking_id">Booking ID:</label>
            <input type="number" name="booking_id" id="booking_id" size=10>
            <br><br>
            <label for="business_id">Business ID:</label>
            <input type="number" name="business_id" id="business_id" size=10> 
            <br><br>
            <label for="product_id">Product ID:</label>
            <input type="number" name="product_id" id="product_id" size=10> 
            <br><br>
            <label for="breakage_qty">Quantity Broken:</label>
            <input type="number" name="breakage_qty" id="breakage_qty" size=10>
            <br><br>
            <h4><b>Enter information and click</b></h4>
            <p><input type="submit" value="Submit">
            or
            <input type="reset" value="Reset"></p>
        </form>
    </body> 
</html>