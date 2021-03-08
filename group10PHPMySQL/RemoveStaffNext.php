<!-- 
    Purpose of Script: Remove Staff
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
    <title> Remove Staff </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>
        <?php include 'UniversalMenuBar.php';
        echo '<br>';
        include 'ManagerMenuBar.php';

        include ("ServerDetail.php"); 

        //Set variables from previous page
        $workerID = $_POST["employee_list"];

        //Set a session variable for product ID for the next page
        $_SESSION["workerID"]=$workerID;

        $q  = "DELETE FROM Employees WHERE Worker_ID = $workerID";

        $result = $link->query($q);   
         ?>

        <h2>Your employee's has been removed</h2>
    </body> 
</html>
