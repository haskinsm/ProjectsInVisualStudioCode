<!-- 
    Purpose of Script: Best Customers Report
    Written by: Michael H
    last updated: Michael 19/02/21
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
    <title> Best Customers </title>
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


    <h2> Best Customers: </h2>

    <table>
        <tr> 

            <th> Business ID </th>
            <th> Business Name </th>
            <th> Email </th>
            <th> Revenue (incl. fufilled and future bookings) </th>
        
        </tr>
        <?php
                //Connect to SQL database
                $link = mysqli_connect("localhost","group_10","Ugh3Aiko","stu33001_2021_group_10_db");
            
                //Access the SQL database
                // This will get data for all the bookings: will get business ID, business Name, Business Email and booking ID for each booking  (Business relates to the customer here)
                $sql = "Select Bookings.Business_ID, Business_Name, Business_Email, Booking_ID FROM Customers, Bookings WHERE Bookings.Business_ID = Customers.Business_ID";
                $result = mysqli_query($link,$sql); 

                // Need to establish variables and set to zero. These are necessary to ensure all of the revenue from each customers booking is added to give the overall revenue from the customers, rather than just giving revnue per booking
                $prevBusinessID = ""; 
                $currBusinessID = "";
                $revenueTally = "";
                echo '<tr>'; // This must be outside the while loop of formatting gets messed up

              
                while($row=mysqli_fetch_assoc($result)){
                    $currBusinessID = $row["Business_ID"];
                    $bookingID = $row["Booking_ID"];

                    if( $prevBusinessID != "" && $prevBusinessID != $currBusinessID){ ## Arrived at diff customers booking and not first iteration of loop
                        echo '<td> €'.$revenueTally.'</td>';  ## Revenue Tally at this point should contain all revenue from the customer (rev from all their bookings added up already)
                        echo '</tr>';
                        echo '<tr>';

                    }

                    if( $currBusinessID != $prevBusinessID){ ## Arrived at diff suctomers ID to prev one
                        $revenueTally = ""; ## Reset revnueTally upon new customerID
                        echo '<td>'.$currBusinessID.'</td>';
                        echo '<td>'.$row["Business_Name"].'</td>';
                        echo '<td>'.$row["Business_Email"].'</td>';
                    }
                   

                     //Access the SQL database
                    // This will get the revenue of each customers booking and will be added up
                    $sql2Q = "SELECT sum(Product_Qty * Rental_Fee) FROM Order_Items, Products WHERE Booking_ID = '$bookingID' && Order_Items.Product_ID = Products.Product_ID ";
                    $result2Q = mysqli_query($link,$sql2Q); 

                    while($row2Q=mysqli_fetch_assoc($result2Q)){
                
                       ## echo '<td> €'.$row2Q["sum(Product_Qty * Rental_Fee)"].'</td>';      
                       $revenueTally = $revenueTally + $row2Q["sum(Product_Qty * Rental_Fee)"];
                    }

                    $prevBusinessID = $currBusinessID;
                   
                }
                ## These are needed after the while loop to ensure last entries revenue is entered and the row ended
                echo '<td> €'.$revenueTally.'</td>'; 
                echo '</tr>';


        ?>
    </table>


</body>
</html>