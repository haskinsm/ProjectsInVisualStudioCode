<!-- 
    Purpose of Script: Add new Items 
    Written by: Jason Yang
    last updated: Jason 2/3/21, 3/3/21
    Fixed this page

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
    <title> Add Items </title>
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
        $sql1 = "SELECT Quantity, Product_Name FROM Products WHERE Product_ID = $productID";
        $result1 = mysqli_query($link,$sql1); 
        
        while($row1=mysqli_fetch_assoc($result1)){
            
            $productQty = $row1["Quantity"];
            $productName = $row1["Product_Name"];

            //show number of breakages if we currently have inventory
            if($productQty>0){
                echo "We currently have $productQty of $productName" ;

                $sql2Q = "SELECT Breakage_Qty FROM Breakages WHERE Product_ID = '$productID' ";
                $result2Q = mysqli_query($link,$sql2Q);
                
                while($row2Q=mysqli_fetch_assoc($result2Q)){
                        
                    $breakageQty = $row2Q["Breakage_Qty"];

                    if($breakageQty>0){
                        echo ", and $breakageQty breakages, what is the new quantity?";
                    } else {
                        echo ", and no breakages, what is the new quantity?";
                    }
                }
            } else {
                echo "We currently have none of $productName ";
            }
        }
         ?>

        <form action="AdjustItemQuantityDetail.php" method="post" name="signupform" id="signupform">
        <label for="newQty">New Quantity:</label>
        <input type="number" name="newQty" id="newQty" size=20>
        <h4><b>Enter information and click</b></h4>
            <p><input type="submit" value="Submit">
            or
            <input type="reset" value="Reset"></p>
        </form>
    </body> 
</html>
