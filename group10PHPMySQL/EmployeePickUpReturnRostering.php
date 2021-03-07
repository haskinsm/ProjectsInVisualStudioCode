<!-- 
    Purpose of Script: Employee Rostering, the aim for this page is to have an area at the top where managers can assign employees and delivery drivers to pickup & return bookings (hopefully with dropdown bars) and 
                            below that to display all bookings for the next month booking ID, assigned drivers or null (blank).
    Written by: Michael H
    last updated: 
                Michael 07/03/21
                    Written. Temp commented out the line of code importing managerMenuBar while Harry fixes it so I can work here. This page is the image of employeeeRostering just with relevant variables & names changed.
                    Also added delete feature.
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Employee Rostering for pickup & return bookings </title>
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
        td {
            text-align: center; 
        }
    </style>
</head>
<body>

    <?php include 'UniversalMenuBar.php';?> <!-- Imports code for menu bar from another php file-->

    <br>

    <?php include 'ManagerMenuBar.php';?> <!-- Imports code for manager menu bar from another php file-->


    <?php
        $deliveryVanID = 0; // Set to 0 as irrelevant for Pickups and returns by customers

        // Will enter here once submit has been hit, will take in the function (Assign or Delete) bookingID, workerID, deliveryVanID from the submitted form
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        	
            ## As using dropdowns only need to check that Booking ID & worker ID are not empty (i.e. no worker or booking after todays date in the db)
            if (!empty($_POST["bookingID"]) && !empty($_POST["workerID"])) { 
                $function = $_POST["function"];
                $bookingID = $_POST["bookingID"];
                $workerID = $_POST["workerID"];
                $delivOrColl = $_POST["delivOrColl"]; ## Should Either be 'Pickup' or 'Return'

                $dataEntered = True;

                //Connect to SQL database
                include ("ServerDetail.php");
                
                ## If Assign make a new assignment, if delete then delete a specified assignment
                if( $function == "Assign"){
                    // Access the SQL database
                    // This will insert the entered data into the roster table 
                    $sqlIns = "INSERT INTO Roster Values ( '$workerID', '$bookingID', '$deliveryVanID', '$delivOrColl' )";
                    $resultIns = mysqli_query($link,$sqlIns);
                }   else{
                    // Delete specified booking. If there is no match will do nothing.
                    $sqlDel = "DELETE FROM Roster WHERE Worker_ID = '$workerID' && Booking_ID = '$bookingID' && Vehicle_ID = '$deliveryVanID' && Function = '$delivOrColl'";
                    $resultDel = mysqli_query($link,$sqlDel);
                }   
                // Table refreshes so user can see the result of their assignment or deletion, so I think there is no need to output result of the sql query e.g. if assignment has been deleted or not 
            }
        }
    ?>

    
    <script language="javascript">	
        // Will enter below condition if date has been submitted 
        if( "<?php echo $dateEntered ?>"){
             document.location.replace("EmployeePickUpReturnRostering.php");  // This is needed to refresh the table below when data is entered
        }
    </script>

    <h2> This is for Customer pickup and return bookings only.  </h2>

    <h2> Assign a worker to a bookings pickup or return job or delete a previously made assignment: </h2>
       
    <!-- 
        The below form lets a manager assign an employee and Van to a booking. There is no check for van or employee availbaility, as it would take too much time to do this given time constraints of the project
        It is possible to assign multiple vans & employees to a booking. For example if you assign two wrokers to deliver & setup booking 14 it will appear as two rows
    -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
        <table>
            <tr>
                <!-- Will allow user to decide if they want to delete a roster assignment or make a new assignment -->
                <td> Function: </td>
                <td class="dropdown" >
                    <select name="function">
                       <option value="Assign"> Assign </option>
                       <option value="Delete"> Delete </option>
                    </select>       
                </td>  
            </tr>
            <tr>
                <td> Booking ID: </td> 
                <td class="dropdown" >
                    <select name="bookingID">
                        <?php
                            // Connect to SQL database
                            include ("ServerDetail.php");
                            // This query will get all booking IDS their start and end dates and only display bookings who have an end date that is greater than or equal to todays date and only Bookings that are to be picked up and returned by the user
                            $sqlQ2 = "Select Booking_ID, Event_Start_Date, Event_End_Date FROM Bookings Where Event_End_Date >= CURRENT_DATE() && Delivery_Status = 'N/a' ORDER BY Event_Start_Date ASC";
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
            </tr>
            <tr>
                <td> Assign worker: </td>
                <td class="dropdown" >
                    <select name="workerID">
                        <?php

                            $sqlQ3 = "Select Worker_ID, Worker_Name FROM Employees";
                            $resultQ3 = mysqli_query($link,$sqlQ3); 

                            while($rowQ3 = mysqli_fetch_assoc($resultQ3)){
                                $emplID = $rowQ3["Worker_ID"];
                                $name = $rowQ3["Worker_Name"];
                                echo '<option value='.$emplID.'>'.$name.' (Worker ID: '.$emplID.')</option>';
                            }

                        ?>
                    </select>
                </td>            
            </tr>
            <tr>
                <td> Activity: </td>  
                <td class="dropdown" >
                    <select name="delivOrColl">
                        <option value="Pickup" > Pickup </option>
                        <option value="Return" > Return </option>
                    </select>
                </td>        
            </tr>
            <tr>
                <td>
                    <input type="submit" name = "Submit" value = "Submit">
                </td>
            </tr>
        </table>   
    </form> 

    <h3> 
        Please note that you are allowed assign multiple workers to a booking's pickup & return jobs and these will appear as seperate rows in the below table. 
        <br>
        This means for example that a booking may have multiple rows of pickups.
    </h3>

    <br>

    <h2> Bookings: </h2>

    <table>
        <tr> 
            <th> Booking ID </th>
            <th> Activity </th>
            <th> Date  </th>
            <th> Time   </th>
            <th> Status </th>
            <th> Assigned Worker's ID</th>
        
        </tr>
        <?php
            
            // Connect to SQL database
            include ("ServerDetail.php");
        
            //Access the SQL database
            // This will get BookingID, start & end date & time, delivery & collection status, assigned employees & delivery vans (if any assigned).
            // A left join was used so that every entry from Bookings was included even if there was no matching entries in the Roster table.
            //  Only bookings who have an end date after todays date are included.
            // This means that deliverys which may have already happended and have a booking end date after todays date will be included in the report the way it is formmated atm 06/03/21
            // Bookings which are being pickedup/returned by the cutsomer will only appear in this report as ignores bookings where Delivery_Status != 'N/a' i.e. Delivery/Collection by DPH bookings
            // The query is ordered by event_end_date, Booking_ID and Function (which is a field in our table)
            $sql = "SELECT Bookings.Booking_ID, Event_Start_Date, Event_End_Date, Event_Start_Time, Event_End_Time, Return_Status, Collection_Status, Worker_ID, Function FROM Bookings Left JOIN Roster on Bookings.Booking_ID = Roster.Booking_ID WHERE Event_End_Date >= Current_Date() && Delivery_Status = 'N/a' ORDER BY Event_Start_Date ASC, Booking_ID ASC, Function ASC";
            // Note is it essential that the above query is ordered by Booking_ID or the below formatting will not work
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
                $currDelivOrColl = $row["Function"]; ## Can have values NULL, Pickup or Return 

                //specify the format of our dates
                $datestep1 = $row["Event_Start_Date"];
                $datestep2  = strtotime($datestep1);
                $datefinal1 = date("d M Y", $datestep2); 

                $datestep3 = $row["Event_End_Date"];
                $datestep4  = strtotime($datestep3);
                $datefinal2 = date("d M Y", $datestep4); 
    
                if( empty($currDelivOrColl) ){ ## i.e. NULL, this only occurs when neither delivery or collection has been assigned for a booking

                    ## Below if statement is need if there is no record for collection for previous booking
                    if( $currBookingID != $prevBookingID && ($prevDelivOrColl == "Pickup" )){ ## Will enter if there has been no return job assigned for a previous job.e. $prevDelivOrColl == "Pickup" 
                        ## Will display the previous jobs returns (details will not be same to current job so need to use 'prev' varaibels) 
                        echo '<td>'.$prevBookingID.'</td>';
                        echo '<td> Return </td>';
                        echo '<td>'.$prevEndDate.'</td>';
                        echo '<td>'.$prevEndTime.'</td>';
                        echo '<td>'.$prevCollStatus.'</td>';
                        echo '<td> Unassigned </td>';

                        echo '</tr>'; ## End of row
                    }


                    ## Pickup row of current booking
                    echo '<td>'.$currBookingID.'</td>';
                    echo '<td> Pickup </td>';
                    echo '<td>'.$datefinal1.'</td>';
                    //echo '<td>'.$row["Event_Start_Date"].'</td>';
                    echo '<td>'.$row["Event_Start_Time"].'</td>';
                    echo '<td>'.$row["Collection_Status"].'</td>'; ## The collection_status is pick_up status field
                    echo '<td> Unassigned </td>';
                    $a = $row["Worker_ID"]; ## a here is used to empty the workerID of the null values
                    $a = "";

                    echo '</tr>'; ## End of row

                    ## Return row
                    echo '<td>'.$currBookingID.'</td>';
                    echo '<td> Return </td>';
                    echo '<td>'.$datefinal2.'</td>';
                    //echo '<td>'.$row["Event_End_Date"].'</td>';
                    echo '<td>'.$row["Event_End_Time"].'</td>';
                    echo '<td>'.$row["Return_Status"].'</td>';
                    echo '<td> Unassigned </td>';
                    $currDelivOrColl = "Return"; ## Need to set this here in order to achieve desired formating. This variable will set the prevDelivOrColl variable to equal this at the end of the loop and
                                                               ## then that will be used in if statements.

                } elseif( $currDelivOrColl == "Pickup"){  ## Enters if delivery
                    if( $currBookingID != $prevBookingID && $currDelivOrColl == $prevDelivOrColl){ ## Will enter if there has been no return assigned for a previous job.e. $prevDelivOrColl == "Pickup"
                        ## Will display the previous jobs returns (details will not be same to current job so need to use 'prev' varaibels) 
                        echo '<td>'.$prevBookingID.'</td>';
                        echo '<td> Return </td>';
                        echo '<td>'.$prevEndDate.'</td>';
                        echo '<td>'.$prevEndTime.'</td>';
                        echo '<td>'.$prevCollStatus.'</td>';
                        echo '<td> Unassigned </td>';

                        echo '</tr>'; ## End of row

                        ## Below are the entries for the current jobs pickup
                        echo '<td>'.$currBookingID.'</td>';
                        echo '<td> Pickup </td>';
                        echo '<td>'.$datefinal1.'</td>';
                        //echo '<td>'.$row["Event_Start_Date"].'</td>';
                        echo '<td>'.$row["Event_Start_Time"].'</td>';
                        echo '<td>'.$row["Collection_Status"].'</td>';
                        echo '<td>'.$row["Worker_ID"].'</td>';

                    } else{ ## Will enter here if there has been an entry for the privious jobs return (i.e. not missing a return)
                        echo '<td>'.$currBookingID.'</td>';
                        echo '<td> Pickup </td>';
                        echo '<td>'.$datefinal1.'</td>';
                        //echo '<td>'.$row["Event_Start_Date"].'</td>';
                        echo '<td>'.$row["Event_Start_Time"].'</td>';
                        echo '<td>'.$row["Collection_Status"].'</td>';
                        echo '<td>'.$row["Worker_ID"].'</td>';

                        ## Need to assign session variables in case this booking is missing its return job
                        $prevEndDate = $datefinal2;
                        //$prevEndDate = $row["Event_End_Date"];
                        $prevEndTime = $row["Event_End_Time"];
                        $prevCollStatus =  $row["Return_Status"];
                    }    

                } elseif( $currDelivOrColl == "Return"){ ##Enters if Return
                   if( $currBookingID == $prevBookingID){ ## Will enter if row entry above was not the same. If it is the same have two or more workers/vans assigned to a booking so need to fromat accordingly 
                        echo '<td>'.$currBookingID.'</td>';
                        echo '<td>'.$currDelivOrColl.'</td>';
                        echo '<td>'.$datefinal2.'</td>';
                        //echo '<td>'.$row["Event_End_Date"].'</td>';
                        echo '<td>'.$row["Event_End_Time"].'</td>';
                        echo '<td>'.$row["Return_Status"].'</td>';
                        echo '<td>'.$row["Worker_ID"].'</td>';
                        $datefinal1 = $row["Event_Start_Time"] = $row["Collection_Status"] = "";
                        //$row["Event_Start_Date"] = $row["Event_Start_Time"] = $row["Delivery_Status"] = ""; ## Dont need these entries in this row so take and discard 

                    } else{     ## if current booking id is not equal to prev booking id it means that there is no entry for pickup for this job in the roster table
                        ## First enter missing pickup job
                        echo '<td>'.$currBookingID.'</td>';
                        echo '<td> Pickup </td>';
                        echo '<td>'.$datefinal1.'</td>';
                        //echo '<td>'.$row["Event_Start_Date"].'</td>';
                        echo '<td>'.$row["Event_Start_Time"].'</td>';
                        echo '<td>'.$row["Collection_Status"].'</td>';
                        echo '<td> Unassigned </td>';

                        echo '</tr>'; ## End of row

                        ## Then the return job
                        echo '<td>'.$currBookingID.'</td>';
                        echo '<td>'.$currDelivOrColl.'</td>';
                        echo '<td>'.$datefinal2.'</td>';
                        //echo '<td>'.$row["Event_End_Date"].'</td>';
                        echo '<td>'.$row["Event_End_Time"].'</td>';
                        echo '<td>'.$row["Return_Status"].'</td>';
                        echo '<td>'.$row["Worker_ID"].'</td>';
                        
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