<!-- 
    Purpose of Script: Customer check status of order
    Written by: Jason Yang
-->

<?php
    // Start the session
    session_start();

   
     if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["Position"]) && $_SESSION["Position"] === Customer)){
    	header("location: CustomerLogin.php");
    	exit;
     }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Order Check </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
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

    <?php include 'UniversalMenuBar.php';
    echo '<br>';
    include 'CustomerMenuBar.php';

        // define variables and set to empty values
        $bookingID = "";
        $bookingIDErr = "";
        $selectedBookingID = "";


        // Will enter here once submit has been hit, BookingID will be stored as a session variable and any obv errors will be recorded
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        	
            if (empty($_POST["bookingID"])) {
                $bookingIDErr = "Order ID is required";
            } else {
                $bookingID = $_POST["bookingID"];
                if ( !is_numeric($bookingID) ){
                    $bookingIDErr = "Invalid Order ID. Must contain numbers only.";
                }
            }

            ## Will only enter if no discovered errors
            if( $bookingIDErr == "" ){
                $iDEntered = True;
            }
        }
    ?>


    <h2> Enter Order ID: </h2>
       
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
        <table>
            <tr>
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
    </form>                     <input type="submit" name = "Submit" value = "Submit">
                </td>
            </tr>
        </table>   
    </form> 

    <br>

    <h2> Results of Order Check: </h2>

    <table>
        <tr> 
            <th> Order ID </th>
            <th> Date </th>
            <th> Time </th>
            <th> Status </th>
        
        </tr>
        <?php
            if( $iDEntered){
                //Connect to SQL database
                include ("ServerDetail.php");
            
                //Access the SQL database
                // This will get the bookingID, start & end date & time, the delivery status for the entered booking above
                $sql = "SELECT Booking_ID, Event_Start_Date, Event_End_Date, Event_Start_Time, Event_End_Time, Delivery_Status, Collection_Status FROM Bookings Where Booking_ID ='$bookingID' ";
                $result = mysqli_query($link,$sql); 
              
                //Code adapted from Aideen's photo
                while($row=mysqli_fetch_assoc($result)){
                    echo '<tr>';

                    echo '<td>'.$row["Booking_ID"].'</td>';
                    echo '<td>'.$row["Event_Start_Date"].'</td>';
                    echo '<td>'.$row["Event_Start_Time"].'</td>';
                    echo '<td>'.$row["Delivery_Status"].'</td>';

                    echo '</tr>';

                    echo '<td></td>';
                    echo '<td>'.$row["Event_End_Date"].'</td>';
                    echo '<td>'.$row["Event_End_Time"].'</td>';
                    echo '<td>'.$row["Collection_Status"].'</td>';

                    echo '</tr>';
                }
            }


        ?>
    </table>


</body>
</html>