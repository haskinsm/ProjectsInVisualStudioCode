<!-- 
    Purpose of Script: Adjust Worker Hours
    Written by: Jason Yang
    last updated: Jason 4/3/21
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
        $newStart = $_POST["newStart"];  
        $newEnd = $_POST["newEnd"];   
        $workerID = $_SESSION["workerID"];
        $shiftID = $_SESSION["shiftID"];
        
        //Checks to see if new start time is entered
        if(!$newStart)
        {
            echo "Please enter the edited start time.";
            exit;
        }

        //Checks to see if new end time is entered
        if(!$newEnd)
        {
            echo "Please enter the edited end time.";
            exit;
        }

        //Check if session variable gotten correctly
        if(!$workerID)
        {
            echo "Error with worker ID session variable.";
            exit;
        }

        //Check if session variable gotten correctly
        if(!$shiftID)
        {
            echo "Error with shift ID session variable.";
            exit;
        }

        //Insert all values into SQL database
        $q  = "UPDATE Shifts SET Clock_In_Time = '$newStart', Clock_Out_Time = '$newEnd' WHERE Worker_ID = $workerID AND Shift_ID = $shiftID";

        $result = $link->query($q);   
        
        ?>
        <h2>Worker Hours Updated Successfully!</h2>
    </body> 
</html>
