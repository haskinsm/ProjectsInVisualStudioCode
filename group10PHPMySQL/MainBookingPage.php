<!-- 
    Purpose of Script: Users should only be broought here once they have selceted dates in the PickBookingDetails.php page. The avaialable qty with respect to their dates will be shown here
    Written by: Michael H
    last updated: Michael 01/03/21
-->

<?php
    // Start the session
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Main Booking Page </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 

</head>
<body>

    <?php include 'UniversalMenuBar.php';?> <!-- Imports code for menu bar from another php file-->

    <br> 

    <h2> Booking Form </h2>

    <?php 
        $startDate = $_SESSION["startDate"];
        $endDate = $_SESSION["endDate"];
        ## Now display start and end dates selceted to user 
        echo '<h3> Start date selected:  '.date('d-m-Y', strtotime($startDate) ).'. <br> End date selected:  '.date('d-m-Y', strtotime($endDate) ).'. </h3>'; 
        ## Code above also converts the dates back to more common d-m-Y format
    ?>

     <!-- Now create booking form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
        <table>
            <tr>
                <th></th>
                <th></th>
                <th> Rental price per 48hr period </th>
                <th> Optional Set-up Cost per item </th>
            </tr>
            <tr>
                <td> Event Start Time</td>
                <td class="dropdown">
                    <select name="startTime">
                        <option value="Morning"> Morning </option>
                        <option value="Afternoon"> Afternoon </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td> Event End Time</td>
                <td class="dropdown">
                    <select name="endTime">
                        <option value="Morning"> Morning </option>
                        <option value="Afternoon"> Afternoon </option>
                    </select>
                </td>
            </tr>

            <!-- Will output product entries in below php block -->
            <?php
                ## now will create a query to take in all necessary info for the booking

                ## Include database connect file
                require_once "ServerDetail.php"; ## This will connect to db

                ## Now Access the SQL database 
                ## This query will 
                $sql = "SELECT Product_ID, Product_Name, Rental_Fee, Setup_Cost, Quantity From Products Where Quantity > 0";
                $result = mysqli_query($link,$sql); 

            
                while($row = mysqli_fetch_assoc($result) ){
                    
                    $prodId = $row["Product_ID"];
                    ## ************ WARNING below query will slighlty underestimate the availability of items for selected dates. Will come back and fix time permitting (and delete this comment) - Michael *****************************

                    ## Now for nested SQL query 2, this query will get the availability of each product for selected dates
                    $sqlQ2 = "SELECT sum(Product_Qty) FROM Order_Items, Bookings Where Product_ID = '$prodId' && Bookings.Booking_ID = Order_Items.Booking_ID && ( (Event_Start_Date >= '$startDate' && Event_Start_Date <= '$endDate') || (Event_End_Date >= '$startDate' && Event_End_Date <= '$endDate' ) )";
                  
                    ## Tested following query with start date hardcoded as '2021-02-24' and end date as '2021-02-28' and Product_ID ='5' in phpmyadmin and achieved desired results. Then replaced what I was selecting with sum(Product_Qty) to get finished (albeit flawed) query.
                    ## SELECT Bookings.Booking_ID, Product_Qty, Event_Start_Date, Event_End_Date FROM Order_Items, Bookings Where Product_ID = '5' && Bookings.Booking_ID = Order_Items.Booking_ID && ( (Event_Start_Date >= '2021-02-24' && Event_Start_Date <= '2021-02-28') || (Event_End_Date >= '2021-02-24' && Event_End_Date <= '2021-02-28' ) )
                   
                    $resultQ2 = mysqli_query($link,$sqlQ2);

                    $prodAvailable = $row["Quantity"]; ## Initiate at max and then adjust below based on how much has been ordered already. The way sql query one is written will ensure Quantiy is >0.
                    while($rowQ2 = mysqli_fetch_assoc($resultQ2) ){ ## Will enter here if product has items ordered. If null no items ordered so no need to reduce qty of product available
                        $qtyAlreadyOrdered = $rowQ2["sum(Product_Qty)"];
                        $prodAvailable = $prodAvailable - $qtyAlreadyOrdered;
                    }

                    
                    $prodName = $row["Product_Name"];
                    $price = $row["Rental_Fee"];
                    $setupCost = $row["Setup_Cost"];
                    ## Now add to booking form
                    echo '<tr>';
                        echo '<td>'.$prodName.'</td>';
                        echo '<td class = "dropdown">';
                            ## Below line will result in only 8 dropwdown options beeing displayed at a time and then you can scroll down 
                            echo '<select name ="'.$prodName.'" onmousedown="if(this.options.length>8){this.size=8;}"  onchange="this.size=0;" onblur="this.size=0;" >' ; ## Src: https://stackoverflow.com/questions/8788245/how-can-i-limit-the-visible-options-in-an-html-select-dropdown
                            $counter = 0;
                            while( $counter <= $prodAvailable ){ ## This will create a dropdown list from 0 to max Availability
                                echo '<option value='.$counter.'>'. $counter.'</option>';  # Desired code for first iteration: <option value=0> 0 </option>
                                $counter = $counter + 1;
                            }
                        echo '</td>';
                        echo '<td> €'.$price.' </td>';
                        echo '<td> €'.$setupCost.' </td>';
                    echo '</tr>';
                }
            ?>
        </table>
    </form>
    
</body>

        <!-- Code for dropdown:
            <tr>
                <td> Test TestTest</td>
                <td class="dropdown">
                    <select name="testDrop">
                        <option value="1">  1 </option>
                        <option value="2">  2 </option>
                        <option value="3">  3 </option>
                    </select>
                </td>
            </tr> -->
</html>