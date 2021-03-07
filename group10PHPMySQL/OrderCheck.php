<!-- 
    Purpose of Script: Order Check enter Booking ID and then display whether order has been collected/delivered
    Written by: Michael H
    last updated: Michael 17/02/21, Michael 22/02/21
                    written, minor comment update
                  Michael 07/03/21
                    Fixed this report up so it now works for pick up & return bookings too
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Order Check </title>
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
        // define variables and set to empty values
        $selectedBookingID = "";
        $bookingIDErr = "";

        // Will enter here once submit has been hit, BookingID will be stored as a session variable and will check if the bookings ID field is not empty. This will only occur if there are no bookings with end dates after todays date
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        	
            if (empty($_POST["bookingID"])) {
                $bookingIDErr = "Booking ID is required";
            } else {
                $selectedBookingID = $_POST["bookingID"];
            }

            ## Will only enter if no discovered errors
            if( $bookingIDErr == "" ){
                $iDEntered = True;
            }
        }
    ?>

    <!--
    <script language="javascript">	
        // Will enter below condition if date has been submitted and user will be redirected to the report page
        if( "************************php echo $dateEntered ?>"){
             document.location.replace("FormCompletion.php");
        }
    </script>
    -->

    <h2> Enter Booking ID: </h2>
       
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
        <table>
            <td> Booking ID: </td> 
                <td class="dropdown" >
                    <select name="bookingID">
                        <?php
                            // Connect to SQL database
                            include ("ServerDetail.php");
                            // This query will get all booking IDS their start and end dates and only display DPH delivered/collected bookings who have an end date that is greater than or equal to todays date
                            $sqlQ2 = "Select Booking_ID, Event_Start_Date, Event_End_Date FROM Bookings Where Event_End_Date >= CURRENT_DATE() ORDER BY Event_Start_Date ASC";
                            $resultQ2 = mysqli_query($link,$sqlQ2); 

                            while($rowQ2 = mysqli_fetch_assoc($resultQ2)){
                                $bookingID = $rowQ2["Booking_ID"];
                                $start = $rowQ2["Event_Start_Date"];
                                $end = $rowQ2["Event_End_Date"];
                                echo '<option value='.$bookingID.'> Booking ID: '.$bookingID.' (Starts: '. date("d M Y", strtotime($start)).', Ends: '.date("d M Y", strtotime($end) ).')</option>';
                                // Dates are formmated nicely above so 2021-03-05 would read 5 Mar 2021
                            }

                        ?>
                    </select>
                </td>         
            <tr>
                <td>
                    <input type="submit" name = "Submit" value = "Submit">
                </td>
            </tr>
        </table>   
    </form> 

    <br>

    <h2> Results of Order Check: </h2>

    <table>
        <tr> 
            <th> Booking ID </th>
            <th> Date </th>
            <th> Time </th>
            <th> Set-up Status</th>
            <th> Status </th>
            <th> Special Instructions </th>
        
        </tr>
        <?php
            if( $iDEntered){
            
                //Access the SQL database
                // This will get the bookingID, start & end date & time, the Setup & delivery & return & collection (Doubles up as pickup status too) status for the entered booking above
                $sql = "SELECT Booking_ID, Event_Start_Date, Event_End_Date, Event_Start_Time, Event_End_Time, Set_Up, Delivery_Status, Collection_Status, Return_Status, Special_Instructions FROM Bookings Where Booking_ID ='$selectedBookingID' ";
                $result = mysqli_query($link,$sql); 
              
                while($row=mysqli_fetch_assoc($result)){
                    echo '<tr>';

                    ## In the first row the delivery/pickup info will be displayed
                    echo '<td>'.$row["Booking_ID"].'</td>';
                    echo '<td>'.$row["Event_Start_Date"].'</td>';
                    echo '<td>'.$row["Event_Start_Time"].'</td>';
                    echo '<td>'.$row["Set_Up"].'</td>';

                    $deliveryStatus = $row["Delivery_Status"];
                    $collectionStatus = $row["Collection_Status"]; ## This field doubles up as collection and pickup status. So like for a dlivered by DPH booking this will be like when the worker collects the order from the customer
                    ## And for a customer picked up booking this will be the stsatus of whether the customer has collected their order from dph

                    ## Enters below if statement if product is being delivered by DPH and the else if booking is being collected by user
                    if( $deliveryStatus != "N/a"){ 
                        echo '<td>'.$deliveryStatus.'</td>';
                    } else{
                        echo '<td>'.$collectionStatus.'</td>';
                    }
                    echo '<td>'.$row["Special_Instructions"].'</td>';

                    echo '</tr>';


                    ## In the 2nd row the collection/return info will be displayed
                    echo '<td></td>'; ## Don't output booking ID twice
                    echo '<td>'.$row["Event_End_Date"].'</td>';
                    echo '<td>'.$row["Event_End_Time"].'</td>';
                    echo '<td></td>'; ## Don't outputSet up status twice

                    $returnStatus = $row["Return_Status"];
                    ## Enters below if statement if product is being delivered by DPH and the else if booking is being collected by user
                    if( $deliveryStatus != "N/a"){ 
                        echo '<td>'.$collectionStatus.'</td>';
                    } else{
                        echo '<td>'.$returnStatus.'</td>';
                    }
                    echo '<td> </td>'; ## No special instructions for collection

                    echo '</tr>';
                }
            }
            $iDEntered = FALSE;


        ?>
    </table>


</body>
</html>