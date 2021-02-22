<!-- 
    Purpose of Script: Order Check enter Booking ID and then display whether order has been collected/delivered
    Written by: Michael H
    last updated: Michael 17/02/21, Michael 22/02/21
                    written, minor comment update
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
        $bookingID = "";
        $bookingIDErr = "";

        // Will enter here once submit has been hit, BookingID will be stored as a session variable and any obv errors will be recorded
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        	
            if (empty($_POST["bookingID"])) {
                $bookingIDErr = "Booking ID is required";
            } else {
                $bookingID = $_POST["bookingID"];
                if ( !is_numeric($bookingID) ){
                    $bookingIDErr = "Invalid Booking ID. Must contain numbers only.";
                }
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
            <tr>
                <td> Booking ID: </td>
                <td> <input type="text" name="bookingID" required> </td>           
            </tr>
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
            <th> Status </th>
        
        </tr>
        <?php
            if( $iDEntered){
                //Connect to SQL database
                $link = mysqli_connect("localhost","group_10","Ugh3Aiko","stu33001_2021_group_10_db");
            
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