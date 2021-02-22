<!-- 
    Purpose of Script: Sales Rev by Product
    Written by: Michael H
    last updated: Michael 17/02/21
-->

<?php
    // Start the session
    session_start();

    $_SESSION = array(); ## To unset all at once
    session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Sales Revenue By Product </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> <!-- All CSS should be added to the WebsiteStyle.css file and then it will be imported here. If want a unique 
            style for something should be done in line like so: E.G:   <h1 style="color:blue;text-align:center;">  This is a heading </h1>       -->
    <style>
        table
        th{
            text-align: left;
            background-color: black;
            color: #ffffff;
            padding: 10px;
        }
    </style>
</head>
<body>

    <?php include 'UniversalMenuBar.php';?> <!-- Imports code for menu bar from another php file-->

    <br>

    <?php include 'ManagerMenuBar.php';?> <!-- Imports code for manager menu bar from another php file-->


    <h2> Sales Revenue: </h2>

    <table>
        <tr> 

            <th> Product ID </th>
            <th> Product Name </th>
            <th> Sales Revenue of Prod (incl. fufilled and future bookings) </th>
        
        </tr>
        <?php
                //Connect to SQL database
                $link = mysqli_connect("localhost","group_10","Ugh3Aiko","stu33001_2021_group_10_db");
            
                //Access the SQL database
                // This will get product ID, name, rental price (per 48hr period)
                $sql = "SELECT Product_ID, Product_Name, Rental_Fee FROM Products ";
                $result = mysqli_query($link,$sql); 
              
                //Code adapted from Aideen's photo
                while($row=mysqli_fetch_assoc($result)){
                    echo '<tr>';

                    $productID = $row["Product_ID"];
                    $rentalFee =  $row["Rental_Fee"];

                    echo '<td>'.$productID.'</td>';
                    echo '<td>'.$row["Product_Name"].'</td>';
                   

                     //Access the SQL database
                    // This will get the number of times and the QTY of the relevant product that has been ordered
                    $sql2Q = "SELECT count(Product_ID), sum(Product_Qty) FROM Order_Items WHERE Product_ID = '$productID' ";
                    $result2Q = mysqli_query($link,$sql2Q); 

                    while($row2Q=mysqli_fetch_assoc($result2Q)){
                        $numBookingsOrderedIn = $row2Q["count(Product_ID)"];

                        // If ordered in No bookings show Sales to be 0 
                        if( $numBookingsOrderedIn == 0){
                            echo  '<td>€0</td>';
                        } else {
                            $qtyOrdered = $row2Q["sum(Product_Qty)"];
                            echo '<td> €'.($qtyOrdered * $rentalFee).'</td>'; // Calculates Revenue 
                        }
                    }

                    echo '</tr>';
                }


        ?>
    </table>


</body>
</html>