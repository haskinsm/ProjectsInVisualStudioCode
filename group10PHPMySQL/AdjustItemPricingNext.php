<!-- 
    Purpose of Script: Change pricing
    Written by: Jason Yang
    last updated: 3/3/21, 4/3/21
    Add functionality for discounts
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
        $productID = $_POST["product_list"];

        //Set a session variable for product ID for the next page
        $_SESSION["productID"]=$productID;

        // This will get employee ID, name
        $sql1 = "SELECT Rental_Fee, Setup_Cost, Product_Name, Euro_Discount FROM Products WHERE Product_ID = $productID";
        $result1 = mysqli_query($link,$sql1); 
        
        while($row1=mysqli_fetch_assoc($result1)){
            
            $rentalFee = $row1["Rental_Fee"];
            $setupCost = $row1["Setup_Cost"];
            $discount = $row1["Euro_Discount"];
            $productName = $row1["Product_Name"];

            echo " $productName currently costs € $rentalFee to rent with a setup cost of € $setupCost and a discount of € $discount" ;
        }
         ?>

        <form action="AdjustItemPricingDetail.php" method="post" name="signupform" id="signupform">
        <!--If I had the time, I would input this as 2 forms each redirecting to a different page so that the user wont have to enter both fields each time-->
        <br>
        Please enter all fields. If there is no change in a cost, please enter same cost as above.
        <br>
        <label for="newRent">New Rental Cost:</label>
        <input type="number" name="newRent" id="newRent" step=".01">
        <label for="newSetup">New Setup Cost:</label>
        <input type="number" name="newSetup" id="newSetup" step=".01">
        <label for="newDiscount">New Discount Cost:</label>
        <input type="number" name="newDiscount" id="newDiscount" step=".01">
        <h4><b>Enter information and click</b></h4>
            <p><input type="submit" value="Submit">
            or
            <input type="reset" value="Reset"></p>
        </form>
    </body> 
</html>
