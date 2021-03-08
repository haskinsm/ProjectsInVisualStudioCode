<!-- 
    Purpose of Script: Edit employee data
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
    <title> Edit Employee Data </title>
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

        // This will get employee ID, name
        $sql1 = "SELECT Worker_ID, Worker_Name, Wage_Per_Hour, Empl_Email FROM Employees WHERE Worker_ID = $workerID";
        $result1 = mysqli_query($link,$sql1); 
        
        while($row1=mysqli_fetch_assoc($result1)){
            
            $workerName = $row1["Worker_Name"];
            $workerWage = $row1["Wage_Per_Hour"];
            $workerEmail = $row1["Empl_Email"];

            echo " $workerName is currently employed at €$workerWage per hour.";
            echo '<br>';
            echo "$workerName's email is $workerEmail " ;
        }
         ?>

        <form action="EmployeeDataDetail.php" method="post" name="signupform" id="signupform">
        <!--If I had the time, I would input this as 2 forms each redirecting to a different page so that the user wont have to enter both fields each time-->
        <br>
        Please enter all fields. If there is no change in a field, please enter same information as above. 
        <br>
        <label for="newWage">New Wage rate per hour:</label>
        <input type="number" name="newWage" id="newWage" step=".01">
        <label for="newEmail">New Email:</label>
        <input type="email" name="newEmail" id="newEmail">
        <h4><b>Enter information and click</b></h4>
            <p><input type="submit" value="Submit">
            or
            <input type="reset" value="Reset"></p>
        </form>
    </body> 
</html>
