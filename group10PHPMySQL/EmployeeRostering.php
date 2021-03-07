<!-- 
    Purpose of Script: Employee Rostering, the aim for this page is to have an area at the top where managers can assign employees and delivery drivers to bookings (hopefully with dropdown bars) and 
                            below that to display all bookings for the next month booking ID, assigned drivers or null (blank).
    Written by: Michael H
    last updated: Michael 22/02/21, 23/02/21
                    written report at the bottom works perfectly but yet to get insert (asssigning workets etc) to work, insert now works
                Jason 26/2/21
                    order table by date and changed date format
                Michael 06/03/21
                    Fixed the report so now it only contains Bookings that are not being collected/returned by customers. Also added dropdowns.
                Michael 07/03/21
                    Temp commented out the line of code importing managerMenuBar while Harry fixes it. Removed data entry checks as no need now that it uses dropdown bars for input. Also added the ability to delete a previously made assignment
-->

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
        td {
            text-align: center; 
        }
    </style>
</head>
<body>

    <?php include 'UniversalMenuBar.php';?> <!-- Imports code for menu bar from another php file-->

    <br>

    <?php include 'ManagerMenuBar.php';?>  <!-- Imports code for manager menu bar from another php file-->


    <?php

        // Will enter here once submit has been hit, will take in the function (Assign or delete) bookingID, workerID, deliveryVanID and activity(e.g. delivery, collection, delivery(incl setup))
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        	
            ## As using dropdowns only need to check that Booking ID & worker ID & Delivery Van ID are not empty (i.e. no worker or van or booking after todays date in the db)
            if (!empty($_POST["bookingID"]) && !empty($_POST["workerID"]) && !empty($_POST["deliveryVanID"])) { 
                $function = $_POST["function"];
                $bookingID = $_POST["bookingID"];     
                $workerID = $_POST["workerID"];
                $deliveryVanID = $_POST["deliveryVanID"];       
                $delivOrColl = $_POST["delivOrColl"];
            
                $dataEntered = True;

                //Connect to SQL database
                include ("ServerDetail.php");

                ## If Assign make a new assignment, if delete then delete a specified assignment
                if( $function == "Assign"){
                    //Access the SQL database
                    // This will insert the entered data into the roster table 
                    $sqlIns = "INSERT INTO Roster Values ( '$workerID', '$bookingID', '$deliveryVanID', '$delivOrColl' )";
                    $resultIns = mysqli_query($link,$sqlIns);
                } else{
                    $sqlDel = "DELETE FROM Roster WHERE Worker_ID = '$workerID' && Booking_ID = '$bookingID' && Vehicle_ID = '$deliveryVanID' && Function = '$delivOrColl'";
                    $resultDel = mysqli_query($link,$sqlDel);
                }    
            }
        }
    ?>

    
    <script language="javascript">	
        // Will enter below condition if date has been submitted 
        if( "<?php echo $dateEntered ?>"){
             document.location.replace("EmployeeRostering.php");  // This is needed to refresh the table below when data is entered
        }
    </script>

    <h2> This is for delivery (may include setup) and collection bookings only.  </h2>

    <h2> Assign a worker to a bookings delivery or collection job Or delete a previously made assignment: </h2>
       
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
                            // This query will get all booking IDS their start and end dates and only display DPH delivered/collected bookings who have an end date that is greater than or equal to todays date
                            $sqlQ2 = "Select Booking_ID, Event_Start_Date, Event_End_Date FROM Bookings Where Event_End_Date >= CURRENT_DATE()  && Delivery_Status != 'N/a' ORDER BY Event_Start_Date ASC";
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
                <td> Assign Delivery Van: </td>  
                <td class="dropdown" >
                    <select name="deliveryVanID">
                        <?php
                
                            $sqlQ4 = "Select  Vehicle_ID, Vehicle_Reg FROM Vehicles";
                            $resultQ4 = mysqli_query($link,$sqlQ4); 

                            while($rowQ4 = mysqli_fetch_assoc($resultQ4)){
                                $vehicleID = $rowQ4["Vehicle_ID"];
                                $vehicleReg = $rowQ4["Vehicle_Reg"];
                                echo '<option value='.$vehicleID.'> Vehicle Reg: '.$vehicleReg.' (Vehicle ID:'.$vehicleID.')</option>';
                            }

                        ?>
                    </select>
                </td>         
            </tr>
            <tr>
                <td> Activity: </td>  
                <td class="dropdown" >
                    <select name="delivOrColl">
                        <option value="Delivery" > Delivery </option>
                        <option value="Delivery&Setup" > Delivery & Setup </option>
                        <option value="Collection" > Collection </option>
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
        Please note that you are allowed assign multiple drivers and vans to a booking and these will appear as seperate rows in the below table. 
        <br>
        This means for example that a booking may have multiple rows of deliveries.
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
            <th> Assigned Delivery Van's ID</th>
        
        </tr>
        <?php
            
            // Connect to SQL database
            include ("ServerDetail.php");
        
            //Access the SQL database
            // This will get BookingID, start & end date & time, delivery & collection status, assigned employees & delivery vans (if any assigned).
            // A left join was used so that every entry from Bookings was included even if there was no matching entries in the Roster table.
            //  Only bookings who have an end date after todays date are included.
            // This means that deliverys which may have already happended will be included in the report the way it is formmated atm 06/03/21
            // Bookings which are being pickedup/returned by the user will not appear in this report as ignores bookings where Delivery_Status = 'N/a' i.e. Pickup/return bookings
            // The query is ordered by event_end_date, Booking_ID and Function (which is a field in our table)
            $sql = "SELECT Bookings.Booking_ID, Event_Start_Date, Event_End_Date, Event_Start_Time, Event_End_Time, Set_Up, Delivery_Status, Collection_Status, Worker_ID, Vehicle_ID, Function FROM Bookings Left JOIN Roster on Bookings.Booking_ID = Roster.Booking_ID WHERE Event_End_Date >= Current_Date() && Delivery_Status != 'N/a' ORDER BY Event_Start_Date ASC, Booking_ID ASC, Function ASC";
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
                $currDelivOrColl = $row["Function"]; ## Can have values NULL, Delivery or Collection or Delivery&Setup
                if( $currDelivOrColl == "Delivery&Setup"){
                    $currDelivOrColl = "Delivery"; ## Will have less checks if I group these together and then can change back when outputting to table
                }

                //specify the format of our dates
                $datestep1 = $row["Event_Start_Date"];
                $datestep2  = strtotime($datestep1);
                $datefinal1 = date("d M Y", $datestep2); 

                $datestep3 = $row["Event_End_Date"];
                $datestep4  = strtotime($datestep3);
                $datefinal2 = date("d M Y", $datestep4); 

                $delivSetup = "Delivery"; ## Will either have the value 'Delivery' or 'Delivery & Setup'
                $setup = $row["Set_Up"]; ## If 'N/a' setup is not applicable to this booking
                if( $setup != "N/a"){
                    $delivSetup = "Delivery & Setup";
                }

                if( empty($currDelivOrColl) ){ ## i.e. NULL, this only occurs when neither delivery or collection has been assigned for a booking

                    ## Below if statement is need if there is no record for collection for previous booking
                    if( $currBookingID != $prevBookingID && ($prevDelivOrColl == "Delivery" )){ ## Will enter if there has been no collection assigned for a previous job.e. $prevDelivOrColl == "Delivery" 
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
                    echo '<td>'.$delivSetup.'</td>';
                    echo '<td>'.$datefinal1.'</td>';
                    //echo '<td>'.$row["Event_Start_Date"].'</td>';
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
                    echo '<td>'.$datefinal2.'</td>';
                    //echo '<td>'.$row["Event_End_Date"].'</td>';
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

                        echo '</tr>'; ## End of row

                        ## Below are the entries for the current jobs delivery
                        echo '<td>'.$currBookingID.'</td>';
                        echo '<td>'.$delivSetup.'</td>';
                        echo '<td>'.$datefinal1.'</td>';
                        //echo '<td>'.$row["Event_Start_Date"].'</td>';
                        echo '<td>'.$row["Event_Start_Time"].'</td>';
                        echo '<td>'.$row["Delivery_Status"].'</td>';
                        echo '<td>'.$row["Worker_ID"].'</td>';
                        echo '<td>'.$row["Vehicle_ID"].'</td>';

                    } else{ ## Will enter here if there has been an entry for the privious jobs collection (i.e. not missing a collection)
                        echo '<td>'.$currBookingID.'</td>';
                        echo '<td>'.$delivSetup.'</td>';
                        echo '<td>'.$datefinal1.'</td>';
                        //echo '<td>'.$row["Event_Start_Date"].'</td>';
                        echo '<td>'.$row["Event_Start_Time"].'</td>';
                        echo '<td>'.$row["Delivery_Status"].'</td>';
                        echo '<td>'.$row["Worker_ID"].'</td>';
                        echo '<td>'.$row["Vehicle_ID"].'</td>';

                        ## Need to assign session variables in case this booking is missing its collection job
                        $prevEndDate = $datefinal2;
                        //$prevEndDate = $row["Event_End_Date"];
                        $prevEndTime = $row["Event_End_Time"];
                        $prevCollStatus =  $row["Collection_Status"];
                    }    

                } elseif( $currDelivOrColl == "Collection"){ ##Enters if Collection
                   if( $currBookingID == $prevBookingID){ ## Will enter if row entry above was not the same. If it is the same have two or more workers/vans assigned to a booking so need to fromat accordingly 
                        echo '<td>'.$currBookingID.'</td>';
                        echo '<td>'.$currDelivOrColl.'</td>';
                        echo '<td>'.$datefinal2.'</td>';
                        //echo '<td>'.$row["Event_End_Date"].'</td>';
                        echo '<td>'.$row["Event_End_Time"].'</td>';
                        echo '<td>'.$row["Collection_Status"].'</td>';
                        echo '<td>'.$row["Worker_ID"].'</td>';
                        echo '<td>'.$row["Vehicle_ID"].'</td>';
                        $datefinal1 = $row["Event_Start_Time"] = $row["Delivery_Status"] = "";
                        //$row["Event_Start_Date"] = $row["Event_Start_Time"] = $row["Delivery_Status"] = ""; ## Dont need these entries in this row so take and discard 

                    } else{     ## if current booking id is not equal to prev booking id it means that there is no entry for delivery for this job in the roster table
                        ## First enter missing Delivery job
                        echo '<td>'.$currBookingID.'</td>';
                        echo '<td>'.$delivSetup.'</td>';
                        echo '<td>'.$datefinal1.'</td>';
                        //echo '<td>'.$row["Event_Start_Date"].'</td>';
                        echo '<td>'.$row["Event_Start_Time"].'</td>';
                        echo '<td>'.$row["Delivery_Status"].'</td>';
                        echo '<td> Unassigned </td>';
                        echo '<td> Unassigned </td>';

                        echo '</tr>'; ## End of row

                        ## Then the collection job
                        echo '<td>'.$currBookingID.'</td>';
                        echo '<td>'.$currDelivOrColl.'</td>';
                        echo '<td>'.$datefinal2.'</td>';
                        //echo '<td>'.$row["Event_End_Date"].'</td>';
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