<!-- 
    Purpose of Script: Users should only be broought here once they have selceted dates in the PickBookingDetails.php page. The avaialable qty with respect to their dates will be shown here
    Written by: Michael H
    last updated: Michael 01/03/21, Michael 02/03/21
                Form partially complete; Has dropdown bars showing availability of each item, Fixed N/a and wrote code for session vars,
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

        ## Store dates as session variables and output to user
        $startDate = $_SESSION["startDate"];
        $endDate = $_SESSION["endDate"];
        ## Now display start and end dates selceted to user 
        echo '<h3> Start date selected:  '.date('d-m-Y', strtotime($startDate) ).'. <br> End date selected:  '.date('d-m-Y', strtotime($endDate) ).'. </h3>'; 
        ## Code above also converts the dates back to more common d-m-Y format
       

        ## Now will calculate how many 48hr periods their booking is for
        ## Convert dates to unix timestamps then substract one from another which will give diff in seconds. Then divide by 86400 (num of seconds in a day)
        $start = strtotime($startDate);
        $end  = strtotime($endDate);
        $diffBetweenDates = ceil(abs($end - $start) / 86400);  ## Note the ceil function rounds up and abs gets the absolute value
        $mod = ( $diffBetweenDates%2 ); ## Should only ever be 1 or 0. Eg: (2)%2=0, (3)%2=1 
        ## Floor is used to round down to nearest integer. Eg: floor(5/2) => floor(2.5) = 2
        $num48hrPeriods = ( floor($diffBetweenDates/2) + $mod ); ## If differnce between dates is 5, should result in 2 + 1 = 3 '48hr' periods
        $_SESSION["num48hrPeriods"] = $num48hrPeriods; ## Stores it as a session variable to be used on next page
        echo '<h3> Number of Billing periods for dates selected: '. $num48hrPeriods.'</h3>'; ## Tell the user the number of billable periods for their dates selected


        $dataEnteredCorrectly = FALSE;
        ## Will enter here once submit has been hit 
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
            ## Since all of the data coming in is from dropdowns dont need to check if data is valid or empty
            $startTime  = $_POST["startTime"];
            $_SESSION["startTime"] = $startTime;

            $endTime = $_POST["endTime"];
            $_SESSION["endTime"] = $endTime;

            $kmOutsideDublin = $_POST["kmFromDublin"];
            $_SESSION["kmOutsideDublin"] = $kmOutsideDublin;

            $numProdsOrdered = 0; ## Will use to ensure that atleast one prod has been ordered

            for($x = 1; $x <= $_SESSION["prodCount"]; $x++){

                $qty = $_POST[ "product".$x ]; ## The 1st prod will be posted as 'product1' so take this in and set it to qty
               
                if( $qty >= 1){
                    $numProdsOrdered = $numProdsOrdered + 1;
                }
                ## Now create a session variable for qty
                $_SESSION["product".$x."qty"] = $qty; ## This will create a session variable storing the prod qty for product 1 as 'product1qty'
            }

            if($numProdsOrdered == 0){ ## Nothing has been ordered, so dont allow them to proceed
                echo '<h3 style = "color:red; text-shadow: -1px -1px 0 #000;"> Warning: No items have been selected </h3>';
                 ## The styling here gives the font a green colour and outlines it will black to increase readability 
            } else {
                $dataEnteredCorrectly = TRUE;
            }
           
        }
    ?>


    <!-- Now redirect user to following page if data has been entered -->
    <script language="javascript">	
        // Will enter below condition if dates have been submitted and user will be redirected to the next booking page
        if( "<?php echo $dataEnteredCorrectly ?>"){
            document.location.replace("BookingConfirmation.php"); // Redirect to next booking page
        } 
    </script>


     <!-- Create booking form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
        <table>
            <tr>
                <th></th>
                <th></th>
                <!-- Styling here gives headers with text a black background and white text and adds some padding to increase readability -->
                <th style="background-color:black; padding:10px; color: #ffffff;"> Rental price for your selected dates </th>
                <th style="background-color:black; padding:10px; color: #ffffff;"> Discount </th>
                <th style="background-color:black; padding:10px; color: #ffffff;"> Rental Price after discount for your selected dates </th>
                <th style="background-color:black; padding:10px; color: #ffffff;"> Optional Set-up Cost per item </th>
            </tr>
            <tr>
                <td> Event Delivery or Pickup Time </td>
                <td class="dropdown">
                    <select name="startTime">
                        <option value="Morning"> Morning </option>
                        <option value="Afternoon"> Afternoon </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td> Event Collection or Return Time </td>
                <td class="dropdown">
                    <select name="endTime">
                        <option value="Morning"> Morning </option>
                        <option value="Afternoon"> Afternoon </option>
                    </select>
                </td>
            </tr>
            <tr>
            <!-- Get user to enter county of event  -->
            <td> County of Event </td>
                <td class="dropdown">
                    <select name="kmFromDublin">
                        <!-- Below values are km distance between that County and Dublin -->
                        <option value="0"> Dublin </option>
                        <option value="45"> Meath </option> 
                        <option value="81.6"> Louth </option>
                        <option value="92"> Westmeath </option>
                        <option value="60.3"> Kildare </option>
                        <option value="66.4"> Wicklow </option>
                        <option value="113.2"> Cavan </option>
                        <option value="130.8"> Monaghan </option>
                        <option value="121.4"> Offaly </option>
                        <option value="120.3"> Longford </option>
                        <option value="96.0"> Laois </option>
                        <option value="90.6"> Carlow </option>
                        <option value="154.9"> Wexford</option>
                        <option value="129.3"> Kilkenny </option>
                        <option value="170.9"> Waterford </option>
                        <option value="188.4"> Tipperary </option>
                        <option value="259.2"> Cork </option>
                        <option value="296.2"> Kerry </option>
                        <option value="202.9"> Limerick </option>
                        <option value="243.7"> Clare </option>
                        <option value="208.4"> Galway </option>
                        <option value="255"> Mayo </option>
                        <option value="209.2"> Sligo </option>
                        <option value="155.4"> Roscommon </option>
                        <option value="156.8"> Leitrim </option>
                        <option value="130.8"> Monaghan </option>
                        <option value="224.4"> Donegal </option>
                        <option value="133.8"> Armagh </option>
                        <option value="160"> Down </option>
                        <option value="161.6"> Fermanagh </option>
                        <option value="179.3"> Tyrone </option>
                        <option value="233.5"> Derry </option>
                        <option value="194.2"> Antrim </option>
                    </select>
                </td>
            </tr>

            <!-- Will output product entries in below php block -->
            <?php
                ## now will create a query to take in all necessary info for the booking

                ## Include database connect file
                require_once "ServerDetail.php"; ## This will connect to db

                ## Now Access the SQL database 
                ## This query will get each products ID, Name, Rental fee, setup cost, qty, discount in euro where qty>0
                $sql = "SELECT Product_ID, Product_Name, Rental_Fee, Setup_Cost, Quantity, Euro_Discount From Products Where Quantity > 0";
                $result = mysqli_query($link,$sql); 

                $prodCount = 0; ## Will be set to qty of products displayed. Need this to help with naming the relevant session variables

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
                    $euroDiscount = $row["Euro_Discount"];
                    $setupCost = $row["Setup_Cost"];
                    $prodCount = $prodCount + 1;
                    ## Now add to booking form
                    echo '<tr>';
                        echo '<td>'.$prodName.'</td>';
                        echo '<td class = "dropdown">';
                            ## Below line will result in only 8 dropwdown options beeing displayed at a time and then you can scroll down 
                            ## The name for the first product will be product1. Cannot have name as product name as there are spaces in the prod names
                            echo '<select name = "product'.$prodCount.'" onmousedown="if(this.options.length>8){this.size=8;}"  onchange="this.size=0;" onblur="this.size=0;" >' ; ## Src: https://stackoverflow.com/questions/8788245/how-can-i-limit-the-visible-options-in-an-html-select-dropdown
                            $counter = 0;
                            ## Will enter first if statement if there is no product available for selected dates, will enter following if statement if there is product avaialable
                            if( $counter > $prodAvailable){
                                echo '<option value='.$counter.'> Unavailable </option>';
                            } else{
                                while( $counter <= $prodAvailable ){ ## This will create a dropdown list from 0 to max Availability
                                    echo '<option value='.$counter.'>'. $counter.'</option>';  # Desired code for first iteration: <option value=0> 0 </option>
                                    $counter = $counter + 1;
                                }
                            }
                        echo '</td>';

                        ## Now show price for their booking, discount, price after discount for their booking and setup cost to the side
                        $itemPriceForTheirBooking = number_format( (float)( $price*$num48hrPeriods ), 2, '.', '' ); ## number_format function rounds to two places so 8-> 8.00, 9.768 -> 9.77 etc
                        echo '<td style="text-align: center; vertical-align: middle;"> €'.($itemPriceForTheirBooking).' </td>'; ## Price per 48hr rental * num of 48 periods of booking
                        ## The styling align texts in the middle of the cell to increase readability
                       
                            ## If there is no dicount just output '-', if there is calc the discount %. This rly improves readability
                        if( $euroDiscount == 0.00){
                            echo '<td style="text-align: center; vertical-align: middle;"> - </td>';
                        } else {
                            ## Below number_format function rounds to two places so 8-> 8.00, 9.768 -> 9.77 etc
                            echo '<td style="text-align: center; vertical-align: middle;">'.number_format( (float)( ($euroDiscount/$price)*100), 2, '.', '' ).'% </td>'; ## Gets % dicount
                        }
                        
                        ## Now output price for their booking after discount
                        echo '<td style="text-align: center; vertical-align: middle;"> <b> €'.number_format( (float)( ($price - $euroDiscount)*$num48hrPeriods), 2, '.', '' ).' </b> </td>'; ## Using bold text tags within the td tags

                        if( $setupCost == 0){
                            echo '<td style="text-align: center; vertical-align: middle;"> N/a </td>';
                        } else{
                            echo '<td style="text-align: center; vertical-align: middle;"> + €'.$setupCost.' </td>'; ## If have time will alow them to select yes or no for set up here for whatever items they want*******************************
                        }

                    echo '</tr>';

                    ## Now create Session variables for each products details.
                    $_SESSION["prod".$prodCount."Price"] = ($price - $euroDiscount); ## For 1st product this should result in the creation of a sesssion var called "prod1Price". This includes any discount
                    $_SESSION["prod".$prodCount."Setup"] = $setupCost; ## For 1st product this should result in the creation of a sesssion var called "prod1Setup"
                    $_SESSION["prod".$prodCount."Name"] = $prodName; ## For 1st product this should result in the creation of a sesssion var called "prod1Name"
                    $_SESSION["prod".$prodCount."ID"] = $prodId; ## For 1st product this should result in the creation of a sesssion var called "prod1ID"

                }

                $_SESSION["prodCount"] = $prodCount; ## Set total count of products as a session variable
            ?>
            
            <tr>
                <td>
                    <input type="submit" name = "Submit" value = "Submit" style="height:20%; width:40%; background-color:aqua; color:black; font-size: 20px; font-weight: bold;">
                    <!-- The styling increases the prominence of the submit button -->
                </td>
            </tr>

        </table>
    </form>
    
</body>

        
</html>