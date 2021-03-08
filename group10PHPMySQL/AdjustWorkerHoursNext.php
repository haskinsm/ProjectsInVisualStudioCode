<!-- 
    Purpose of Script: Adjust Worker Hours
    Written by: Jason Yang
    last updated: 4/3/21
-->
<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Adjust Worker Hours </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>
        <?php include 'UniversalMenuBar.php';
        echo '<br>';
        include 'ManagerMenuBar.php';

        include ("ServerDetail.php"); 

        //Set variables from previous page
        $shiftID = $_POST["date_list"];
        $workerID = $_SESSION["workerID"];

        //Set a session variable for shift ID for the next page
        $_SESSION["shiftID"]=$shiftID;

        $sql1 = "SELECT Clock_In_Time, Clock_Out_Time FROM Shifts WHERE Worker_ID = $workerID AND Shift_ID = $shiftID";
        $result1 = mysqli_query($link,$sql1); 
        
        while($row1=mysqli_fetch_assoc($result1)){
            
            $startTime = $row1["Clock_In_Time"];
            $endTime = $row1["Clock_Out_Time"];

            echo "Currently we have $startTime to $endTime in our records " ;
        }
         ?>

        <form action="AdjustWorkerHoursDetail.php" method="post" name="signupform" id="signupform">
        <br>
        Please enter all fields. If there is no change in a field, please enter same information as above. 
        <br>
        <label for="newStart">Edited Start Time:</label>
        <input type="time" name="newStart" id="newStart">
        <label for="newEnd">Edited End Time:</label>
        <input type="time" name="newEnd" id="newEnd">
        <h4><b>Enter information and click</b></h4>
            <p><input type="submit" value="Submit">
            or
            <input type="reset" value="Reset"></p>
        </form>
    </body> 
</html>
