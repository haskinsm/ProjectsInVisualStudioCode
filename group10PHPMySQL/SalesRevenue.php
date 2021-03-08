<!-- 
    Purpose of Script: Sales Rev by Product
    Written by: Michael H
    last updated: Michael 17/02/21
                    Written
                 Michael 07/03/21 
                    Fixing bug where the sales revenue is incorrectly calculated. Also made the report dates flexible.
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
        td{
                text-align: center; 
                vertical-align: middle;
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
            echo '<h2> All time Sales Revenue: </h2>';
        } else{
            echo '<h2> Sales Revenue between '.date("d M Y", strtotime($eventStartDate)).' and '.date("d M Y", strtotime($eventEndDate)).': </h2>';
            echo '<h3> Please not that this report will only include bookings that have a start date on or after '.date("d M Y", strtotime($eventStartDate)).' and an end date on or before '.date("d M Y", strtotime($eventEndDate)).'.</h3>';
        }
    ?>
    

    <table>
        <tr> 

            <th> Product ID </th>
            <th> Product Name </th>
            <th> Sales Revenue of Prod </th>
        
        </tr>
        <?php
                // Connect to SQL database
                include ("ServerDetail.php");
            
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
                    // This will get the number of times the relevant product has been ordered
                    $sql2Q = "SELECT count(Product_ID) FROM Order_Items, Bookings WHERE Product_ID = '$productID' && Event_End_Date <= '$eventEndDate' && Event_Start_Date >= '$eventStartDate' && Bookings.Booking_ID = Order_Items.Booking_ID";
                
                    $result2Q = mysqli_query($link,$sql2Q); 

                    while($row2Q=mysqli_fetch_assoc($result2Q)){
                       $numBookingsOrderedIn = $row2Q["count(Product_ID)"];

                        // If ordered in No bookings show Sales to be €0 otherwise go to next query 
                        if( $numBookingsOrderedIn == 0){
                            echo  '<td>€0.00</td>';
                        } else {
            
                            $rev = 0;
                            $sqlQ3 = "SELECT Product_ID, Product_Qty, Event_Start_Date, Event_End_Date FROM Order_Items, Bookings WHERE Product_ID = '$productID' && Bookings.Booking_ID = Order_Items.Booking_ID && Event_End_Date <= '$eventEndDate' && Event_Start_Date >= '$eventStartDate'";
            
                            $resultQ3 = mysqli_query($link,$sqlQ3);
                            while($rowQ3=mysqli_fetch_assoc($resultQ3)){
                                ## Now will calculate how many 48hr periods this booking is for
                                ## Convert dates to unix timestamps then substract one from another which will give diff in seconds. Then divide by 86400 (num of seconds in a day)
                                $startDate = $rowQ3["Event_Start_Date"];
                                $endDate = $rowQ3["Event_End_Date"];
                                $start = strtotime($startDate);
                                $end  = strtotime($endDate);
                                $diffBetweenDates = ceil(abs($end - $start) / 86400);  ## Note the ceil function rounds up and abs gets the absolute value
                                $mod = ( $diffBetweenDates%2 ); ## Should only ever be 1 or 0. Eg: (2)%2=0, (3)%2=1 
                                ## Floor is used to round down to nearest integer. Eg: floor(5/2) => floor(2.5) = 2
                                $num48hrPeriods = ( floor($diffBetweenDates/2) + $mod ); ## If differnce between dates is 5, should result in 2 + 1 = 3 '48hr' periods

                                $qty = $rowQ3["Product_Qty"];
                                $rev = $rev + $rentalFee*$qty*$num48hrPeriods;
                            }
                            ## Now format rev nicely before outputting
                            $rev = number_format( (float)( $rev ), 2, '.', ',' ); ## number_format function rounds to two places so 8-> 8.00, 9.768 -> 9.77 etc 4000 -> 4,000.00
                            echo  '<td> <b> €'.$rev.' </b> </td>'; ## Added bold text tags
                        }
                    }

                    echo '</tr>';
                }


        ?>
    </table>


</body>
</html>