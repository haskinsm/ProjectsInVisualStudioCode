<!-- 
    Purpose of Script: Show upcoming tasks
    Written by: Jason Yang
    last updated: Jason 16/02/21, 18/2/21, 19/2/21, 26/2/21, 8/3/21
-->

<?php
    // Start the session
    session_start();


     if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["Position"]) && $_SESSION["Position"] === Worker)){
    	header("location: StaffLogin.php");
    	exit;
     }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Tasks </title>
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

    <?php include 'StaffMenuBar.php';?> <!-- Imports code for manager menu bar from another php file-->

    <h2> Tasks: </h2>

    <table>
        <tr> 

            <th> Booking ID </th>
            <th> Event Start Date </th>
            <th> Event End Date </th>
            <th> Event Start Time </th>
            <th> Event End Time </th>
            <th> Delivery Status </th>
            <th> Collection Status </th>
        
        </tr>
        <?php
                //Connect to SQL database
                include ("ServerDetail.php");
                
                //Call email session variable
                $email = $_SESSION["Email"];

                //find related worker ID
                $sqlinit = "SELECT Worker_ID FROM Employees WHERE Empl_Email = '$email';";
                $resultinit = mysqli_query($link,$sqlinit); 
                
                while($row1=mysqli_fetch_assoc($resultinit)){
                    
                    $workerID = $row1["Worker_ID"];

                    //Access the SQL database
                    // This will get product ID, name, qty (stock) 
                    $sql = "SELECT * FROM Bookings, Roster WHERE Roster.Booking_ID = Bookings.Booking_ID AND Roster.Worker_ID = $workerID ORDER BY Event_Start_Date ASC";
                    $result = mysqli_query($link,$sql); 
                
                    while($row=mysqli_fetch_assoc($result)){
                        echo '<tr>';

                        $bookingID = $row["Booking_ID"];
                        echo '<td>'.$bookingID.'</td>';
                        
                        $startdatestep1 = $row["Event_Start_Date"];
                        $startdatestep2  = strtotime($startdatestep1);
                        $startdatefinal = date("d M Y", $startdatestep2); 
                        echo '<td>'.$startdatefinal.'</td>';

                        $enddatestep1 = $row["Event_End_Date"];
                        $enddatestep2  = strtotime($enddatestep1);
                        $enddatefinal = date("d M Y", $enddatestep2); 
                        echo '<td>'.$enddatefinal.'</td>';
                    
                        echo '<td>'.$row["Event_Start_Time"].'</td>';
                        echo '<td>'.$row["Event_End_Time"].'</td>';
                        echo '<td>'.$row["Delivery_Status"].'</td>';
                        echo '<td>'.$row["Collection_Status"].'</td>';

                        echo '</tr>';
                    }
                }

        ?>
    </table>


</body>
</html>