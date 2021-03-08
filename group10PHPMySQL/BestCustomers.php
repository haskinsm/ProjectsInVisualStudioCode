<!-- 
    Purpose of Script: Best Customers Report
    Written by: Michael Haskins
    last updated: Michael 19/02/21
                    Written
                  Michael 07/03/21
                    Updated to reflect table structure changes and added revenue after vat deliv fees and setup 
                    
-->

<?php
    // Start the session
    session_start();

     if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["Position"]) && $_SESSION["Position"] === Manager)){
    	header("location: ManagerLogin.php");
    	exit;
     }
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


    <?php
        $eventEndDate = "";
        $eventStartDate = "";
    
        // Will enter here once submit has been hit
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        	
            ## echo(date('Y-m-d', strtotime('1999-12-31'))."<br>");  outputs:1999-12-31
            $eventStartDate = date('Y-m-d', strtotime($_POST["startDate"])); ##https://stackoverflow.com/questions/30243775/get-date-from-input-form-within-php
            $eventEndDate = date('Y-m-d', strtotime($_POST["endDate"]));

            $dateEntered = True;
        } else { 
            ## Set default dates to a min and max
            $eventStartDate = '2021-01-01'; ## There will be no orders before this date
            $eventEndDate = '2060-01-01'; ## This will be used as default max date
        }
    ?>

    <br>

    <h2> Please Select Dates for the below report </h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
        <table>
            <tr>
                <td> Start Date:</td>
                <td> <input type="date" name="startDate" required> </td>           
            </tr>
            <tr>
                <td> End Date:</td>
                <td> <input type="date" name="endDate" required> </td>           
            </tr>
            <tr>
                <td>
                    <input type="submit" name = "Submit" value = "Submit">
                </td>
            </tr>
        </table>   
    </form> 

 
    <?php

        if($eventStartDate == '2021-01-01' ){ ## Then working with default dates -> All time
            echo '<h2> All time Best Customers: </h2>';
        } else{
            echo '<h2> Best Customers between '.date("d M Y", strtotime($eventStartDate)).' and '.date("d M Y", strtotime($eventEndDate)).': </h2>';
            echo '<h3> Please not that this report will only include bookings that have a start date on or after '.date("d M Y", strtotime($eventStartDate)).' and an end date on or before '.date("d M Y", strtotime($eventEndDate)).'.</h3>';
        }
    ?>

    <table>
        <tr> 

            <th> Customer ID </th>
            <th> Customer Name </th>
            <th> Email </th>
            <th> Revenue (Ecl. Vat, Discounts and any Delivery or Setup Fees) </th>
            <th> Revenue (Incl. Vat, Discounts and any Delivery or Setup Fees at time of booking) </th>
        
        </tr>
        <?php
                // Connect to SQL database
                include ("ServerDetail.php");
            
                //Access the SQL database
                // This will get data for all the bookings: will get business ID, business Name, Business Email and booking ID for each booking  (Business relates to the customer here)
                $sql = "SELECT Business_ID, Business_Name, Total_Price_Incl_VAT_Del_Setup, Bookings.Business_Email, Booking_ID, Event_Start_Date, Event_End_Date FROM Customers, Bookings WHERE Bookings.Business_Email = Customers.Business_Email && Event_End_Date <= '$eventEndDate' && Event_Start_Date >= '$eventStartDate' ORDER BY Business_ID";
                ## Can't think of a good way to sort this by price, tried Order BY count(Booking_ID) so customers with the most orders would be at the top of the report
                $result = mysqli_query($link,$sql); 

                // Need to establish variables and set to zero. These are necessary to ensure all of the revenue from each customers booking is added to give the overall revenue from the customers, rather than just giving revnue per booking
                $prevBusinessID = ""; 
                $currBusinessID = "";
                $revenueTally = "";
                $totalPriceInclVatDelivSetup = "";
                echo '<tr>'; // This must be outside the while loop of formatting gets messed up

              
                while($row=mysqli_fetch_assoc($result)){
                    $currBusinessID = $row["Business_ID"];
                    $bookingID = $row["Booking_ID"];

                    if( $prevBusinessID != "" && $prevBusinessID != $currBusinessID){ ## Arrived at diff customers booking and not first iteration of loop
                        ## Now format outputs nicely.
                        $revenueTally = number_format( (float)( $revenueTally ), 2, '.', ',' ); ## number_format function rounds to two places so 8-> 8.00, 9.768 -> 9.77 etc
                        $totalPriceInclVatDelivSetup = number_format( (float)( $totalPriceInclVatDelivSetup ), 2, '.', ',' ); 

                        ## Now output
                        echo '<td> €'.$revenueTally.'</td>';  ## Revenue Tally at this point should contain all revenue from the customer (rev from all their bookings added up already)
                        echo '<td> €'.$totalPriceInclVatDelivSetup.'</td>';  ## Total Revenue incl vat, setup and deliv fees Tally at this point should contain all revenue from the customer (rev from all their bookings added up already)
                        echo '</tr>';
                        echo '<tr>';

                    }

                    if( $currBusinessID != $prevBusinessID){ ## Arrived at diff suctomers ID to prev one
                        $revenueTally = ""; ## Reset revnueTally upon new customerID
                        $totalPriceInclVatDelivSetup = ""; ## Reset upon new customer ID

                        echo '<td>'.$currBusinessID.'</td>';
                        echo '<td>'.$row["Business_Name"].'</td>';
                        echo '<td>'.$row["Business_Email"].'</td>';
                    }

                    $totalPriceInclVatDelivSetup = $totalPriceInclVatDelivSetup + $row["Total_Price_Incl_VAT_Del_Setup"];

                    ## Now will calculate how many 48hr periods this booking is for
                    ## Convert dates to unix timestamps then substract one from another which will give diff in seconds. Then divide by 86400 (num of seconds in a day)
                    $startDate = $row["Event_Start_Date"];
                    $endDate = $row["Event_End_Date"];
                    $start = strtotime($startDate);
                    $end  = strtotime($endDate);
                    $diffBetweenDates = ceil(abs($end - $start) / 86400);  ## Note the ceil function rounds up and abs gets the absolute value
                    $mod = ( $diffBetweenDates%2 ); ## Should only ever be 1 or 0. Eg: (2)%2=0, (3)%2=1 
                    ## Floor is used to round down to nearest integer. Eg: floor(5/2) => floor(2.5) = 2
                    $num48hrPeriods = ( floor($diffBetweenDates/2) + $mod ); ## If differnce between dates is 5, should result in 2 + 1 = 3 '48hr' periods
                   

                     //Access the SQL database
                    // This will get the revenue of each customers booking and will be added up
                    $sql2Q = "SELECT sum(Product_Qty * Rental_Fee) FROM Order_Items, Products WHERE Booking_ID = '$bookingID' && Order_Items.Product_ID = Products.Product_ID ";
                    $result2Q = mysqli_query($link,$sql2Q); 

                    while($row2Q=mysqli_fetch_assoc($result2Q)){
                     
                       $totalRevFromBookingForOnePeriod = $row2Q["sum(Product_Qty * Rental_Fee)"];
                       $revenueTally = $revenueTally + $totalRevFromBookingForOnePeriod*$num48hrPeriods; 
                    }

                    $prevBusinessID = $currBusinessID;
                   
                }

                ## Now format outputs nicely.
                $revenueTally = number_format( (float)( $revenueTally ), 2, '.', ',' ); ## number_format function rounds to two places so 8-> 8.00, 9.768 -> 9.77 etc
                $totalPriceInclVatDelivSetup = number_format( (float)( $totalPriceInclVatDelivSetup ), 2, '.', ',' ); 


                ## These are needed after the while loop to ensure last entries revenue is entered and the row ended
                echo '<td> €'.$revenueTally.'</td>'; 
                echo '<td> €'.$totalPriceInclVatDelivSetup.'</td>'; 
                echo '</tr>';


        ?>
    </table>


</body>
</html>