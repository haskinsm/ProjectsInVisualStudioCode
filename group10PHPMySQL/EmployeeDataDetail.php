<!-- 
    Purpose of Script: Edit Employee Data
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
    <title> Edit Employee Data </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>

        <?php include 'UniversalMenuBar.php';
        echo '<br>';
        include 'ManagerMenuBar.php';

        include ("ServerDetail.php"); 
        
        //Set variables from previous page
        $newWage = $_POST["newWage"];  
        $newEmail = $_POST["newEmail"];   
        $workerID = $_SESSION["workerID"]; 
        
        //Checks to see if wage is entered
        if(!$newWage)
        {
            echo "Please enter the new wage rate.";
            exit;
        }

        //Checks to see if email is entered
        if(!$newEmail)
        {
            echo "Please enter the new email.";
            exit;
        }

        //Check if session variable gotten correctly
        if(!$workerID)
        {
            echo "Error with session variable.";
            exit;
        }

        //Insert all values into SQL database
        $q  = "UPDATE Employees SET Wage_Per_Hour = $newWage, Empl_Email = '$newEmail' WHERE Worker_ID = $workerID";

        $result = $link->query($q);   
        
        ?>
        <h2>Employee Data Updated Successfully!</h2>
    </body> 
</html>
