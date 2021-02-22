<!-- 
    Purpose of Script: Employee Rostering, the aim for this page is to have an area at the top where managers can assign employees and delivery drivers to bookings (hopefully with dropdown bars) and 
                            below that to display all bookings for the next month booking ID, assigned drivers or null (blank).
    Written by: Michael H
    last updated: Michael 22/02/21, 
                    written report at the bootom works perfectly but yet to get insert (asssigning workets etc) to work, 
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
    <title> Employee Rostering </title>
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
        $bookingID = $workerID = $deliveryVanID = $delivOrColl = "";
        $bookingIDErr = $workerIDErr = $deliveryVanIDErr = $delivOrCollErr = "";

        // Will enter here once submit has been hit, will take in the bookingID, workerID, deliveryVanID and record any obs errors such as there being nothing entered or the IDS being non-numeric
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        	
            if (empty($_POST["bookingID"])) {
                $bookingIDErr = "Booking ID is required";
            } else {
                $bookingID = $_POST["bookingID"];
                if ( !is_numeric($bookingID) ){
                    $bookingIDErr = "Invalid Booking ID. Must contain numbers only.";
                }
            }

            if (empty($_POST["workerID"])) {
                $workerIDErr = "Worker ID is required";
            } else {
                $workerID = $_POST["workerID"];
                if ( !is_numeric($workerID) ){
                    $workerIDErr = "Invalid Worker ID. Must contain numbers only.";
                }
            }

            if (empty($_POST["deliveryVanID"])) {
                $deliveryVanIDErr = "Delivery van ID is required";
            } else {
                $deliveryVanID = $_POST["deliveryVanID"];
                if ( !is_numeric($deliveryVanID) ){
                    $deliveryVanIDErr = "Invalid Delivery Van ID. Must contain numbers only.";
                }
            }
            if (empty($_POST["delivOrColl"])) {
                $delivOrCollErr = "Delelivery/Collection is required";
            } else {
                $delivOrColl = $_POST["delivOrColl"];
                if ( $delivOrColl != "Collection" && $delivOrColl != "Delivery" ){
                    $delivOrCollErr = "Invalid entry must enter either Delivery or Collection.";
                }
            }

            ## Will only enter if no discovered errors
            if( $bookingIDErr == "" && $workerIDErr == "" && $deliveryVanIDErr && $delivOrCollErr == "" ){
                $dataEntered = True;

                 //Connect to SQL database
                $link = mysqli_connect("localhost","group_10","Ugh3Aiko","stu33001_2021_group_10_db");
            
                //Access the SQL database
                // This will insert the entered data into the roster table 
                $sqlIns = "INSERT INTO Roster (Worker_ID, Booking_ID, Vehichle_ID, Function) Values ( '$workerID', '$bookingID', '$deliveryVanID', '$delivOrColl' )";
                $result = mysqli_query($link,$sqlIns); 
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

    <h2> Enter relevant IDs to assign a worker & delivery driver to a booking: </h2>
       
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
        <table>
            <tr>
                <td> Booking ID: </td>
                <td> <input type="text" name="bookingID" required> </td>           
            </tr>
            <tr>
                <td> Worker ID: </td>
                <td> <input type="text" name="workerID" required> </td>           
            </tr>
            <tr>
                <td> Delivery Van ID: </td>
                <td> <input type="text" name="deliveryVanID" required> </td>           
            </tr>
            <tr>
                <td> Collection or Delivery: </td>
                <td> <input type="text" name="delivOrColl" required> </td>           
            </tr>
            <tr>
                <td>
                    <input type="submit" name = "Submit" value = "Submit">
                </td>
            </tr>
        </table>   
    </form> 

    <br>

    <h2> Bookings: </h2>

    <table>
        <tr> 
            <th> Booking ID </th>
            <th> Delivery/Collection </th>
            <th> Date </th>
            <th> Time </th>
            <th> Status </th>
            <th> Assigned Worker's ID</th>
            <th> Assigned Delivery Van's ID</th>
        
        </tr>
        <?php
            
            //Connect to SQL database
            $link = mysqli_connect("localhost","group_10","Ugh3Aiko","stu33001_2021_group_10_db");
        
            //Access the SQL database
            // This will get BookingID, start & end date & time, delivery & collection status, assigned employees & delivery vans (if any assigned).
            // A left join was used so that every entry from Bookings was included even if there was no matching entries in the Roster table
            $sql = "SELECT Bookings.Booking_ID, Event_Start_Date, Event_End_Date, Event_Start_Time, Event_End_Time, Delivery_Status, Collection_Status, Worker_ID, Vehicle_ID, Function FROM Bookings Left JOIN Roster on Bookings.Booking_ID = Roster.Booking_ID ORDER BY Bookings.Booking_ID ASC, Function ASC";
            $result = mysqli_query($link,$sql); 
            
            $currBookingID = "";
            $prevBookingID = "";
            $prevDelivOrColl = ""; ## Will record if the last row was a delivery or collection
            $currDelivOrColl = "";

            ## 3 below variables are needed to ensure that a collection for a booking is not missed (if it is these variables will be used to create an entry in the table for that booking).
            $prevEndDate = "";
            $prevEndTime = "";
            $prevCollStatus = "";

            while($row=mysqli_fetch_assoc($result)){
                echo '<tr>';

                $currBookingID = $row["Booking_ID"];
                $currDelivOrColl = $row["Function"]; ## Can have values NULL, Delivery or Collection

                if( empty($currDelivOrColl) ){ ## i.e. NULL, this only occurs when neither delivery or collection has been assigned for a booking

                    ## Below if statement is need if there is no record for collection for previous booking
                    if( $currBookingID != $prevBookingID && $prevDelivOrColl == "Delivery"){ ## Will enter if there has been no collection assigned for a previous job.e. $prevDelivOrColl == "Delivery"
                        ## Will display the previous jobs collections (details will not be same to current job so need to use 'prev' varaibels) 
                        echo '<td>'.$prevBookingID.'</td>';
                        echo '<td> Collection </td>';
                        echo '<td>'.$prevEndDate.'</td>';
                        echo '<td>'.$prevEndTime.'</td>';
                        echo '<td>'.$prevCollStatus.'</td>';
                        echo '<td> Unassigned </td>';
                        echo '<td> Unassigned </td>';

                        echo '</tr>'; ## End of row
                    }


                    ## Delivery row of current booking
                    echo '<td>'.$currBookingID.'</td>';
                    echo '<td> Delivery </td>';
                    echo '<td>'.$row["Event_Start_Date"].'</td>';
                    echo '<td>'.$row["Event_Start_Time"].'</td>';
                    echo '<td>'.$row["Delivery_Status"].'</td>';
                    echo '<td> Unassigned </td>';
                    echo '<td> Unassigned </td>';
                    $a = $row["Worker_ID"]; ## a and b here are used to empty the workerID and Vehicle ID of the null values, may cause errors check when running********************************************
                    $b = $row["Vehicle_ID"];
                    $a = $b = "";

                    echo '</tr>'; ## End of row

                    ## Collection row
                    echo '<td>'.$currBookingID.'</td>';
                    echo '<td> Collection </td>';
                    echo '<td>'.$row["Event_End_Date"].'</td>';
                    echo '<td>'.$row["Event_End_Time"].'</td>';
                    echo '<td>'.$row["Collection_Status"].'</td>';
                    echo '<td> Unassigned </td>';
                    echo '<td> Unassigned </td>';
                    $currDelivOrColl = "Collection"; ## Need to set this here in order to achieve desired formating. This variable will set the prevDelivOrColl variable to equal this at the end of the loop and
                                                               ## then that will be used in if statements.

                } elseif( $currDelivOrColl == "Delivery"){  ## Enters if delivery
                    if( $currBookingID != $prevBookingID && $currDelivOrColl == $prevDelivOrColl){ ## Will enter if there has been no collection assigned for a previous job.e. $prevDelivOrColl == "Delivery"
                        ## Will display the previous jobs collections (details will not be same to current job so need to use 'prev' varaibels) 
                        echo '<td>'.$prevBookingID.'</td>';
                        echo '<td> Collection </td>';
                        echo '<td>'.$prevEndDate.'</td>';
                        echo '<td>'.$prevEndTime.'</td>';
                        echo '<td>'.$prevCollStatus.'</td>';
                        echo '<td> Unassigned </td>';
                        echo '<td> Unassigned </td>';

                        ## Below are the entries for the current jobs delivery
                        echo '<td>'.$currBookingID.'</td>';
                        echo '<td>'.$currDelivOrColl.'</td>';
                        echo '<td>'.$row["Event_Start_Date"].'</td>';
                        echo '<td>'.$row["Event_Start_Time"].'</td>';
                        echo '<td>'.$row["Delivery_Status"].'</td>';
                        echo '<td>'.$row["Worker_ID"].'</td>';
                        echo '<td>'.$row["Vehicle_ID"].'</td>';

                    } else{ ## Will enter here if there has been an entry for the privious jobs collection (i.e. not missing a collection)
                        echo '<td>'.$currBookingID.'</td>';
                        echo '<td>'.$currDelivOrColl.'</td>';
                        echo '<td>'.$row["Event_Start_Date"].'</td>';
                        echo '<td>'.$row["Event_Start_Time"].'</td>';
                        echo '<td>'.$row["Delivery_Status"].'</td>';
                        echo '<td>'.$row["Worker_ID"].'</td>';
                        echo '<td>'.$row["Vehicle_ID"].'</td>';

                        ## Need to assign session variables in case this booking is missing its collection job
                        $prevEndDate = $row["Event_End_Date"];
                        $prevEndTime = $row["Event_End_Time"];
                        $prevCollStatus =  $row["Collection_Status"];
                    }    

                } elseif( $currDelivOrColl == "Collection"){ ##Enters if Collection
                   if( $currBookingID == $prevBookingID){ ## Will enter if row entry above was not the same. If it is the same have two or more workers/vans assigned to a booking so need to fromat accordingly 
                        echo '<td>'.$currBookingID.'</td>';
                        echo '<td>'.$currDelivOrColl.'</td>';
                        echo '<td>'.$row["Event_End_Date"].'</td>';
                        echo '<td>'.$row["Event_End_Time"].'</td>';
                        echo '<td>'.$row["Collection_Status"].'</td>';
                        echo '<td>'.$row["Worker_ID"].'</td>';
                        echo '<td>'.$row["Vehicle_ID"].'</td>';
                        $row["Event_Start_Date"] = $row["Event_Start_Time"] = $row["Delivery_Status"] = ""; ## Dont need these entries in this row so take and discard 

                    } else{     ## if current booking id is not equal to prev booking id it means that there is no entry for delivery for this job in the roster table
                        ## First enter missing Delivery job
                        echo '<td>'.$currBookingID.'</td>';
                        echo '<td> Delivery </td>';
                        echo '<td>'.$row["Event_Start_Date"].'</td>';
                        echo '<td>'.$row["Event_Start_Time"].'</td>';
                        echo '<td>'.$row["Delivery_Status"].'</td>';
                        echo '<td> Unassigned </td>';
                        echo '<td> Unassigned </td>';

                        echo '</tr>'; ## End of row

                        ## Then the collection job
                        echo '<td>'.$currBookingID.'</td>';
                        echo '<td>'.$currDelivOrColl.'</td>';
                        echo '<td>'.$row["Event_End_Date"].'</td>';
                        echo '<td>'.$row["Event_End_Time"].'</td>';
                        echo '<td>'.$row["Collection_Status"].'</td>';
                        echo '<td>'.$row["Worker_ID"].'</td>';
                        echo '<td>'.$row["Vehicle_ID"].'</td>';
                        
                    }  
                }

                $prevBookingID = $currBookingID;
                $prevDelivOrColl = $currDelivOrColl;
                echo '</tr>'; ## Ends the table row
            }
        ?>

    </table>


</body>
</html>