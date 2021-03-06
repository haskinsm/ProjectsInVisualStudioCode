<!-- 
    Purpose of Script: create csv
    Written by: Jason Yang
    last updated: 6/3/21
-->

<?php
    // Start the session
    session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Employee Hours </title>
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
    include 'ManagerMenuBar.php';
    include ("ServerDetail.php");

    $id=$_SESSION["id"];

    if(isset($_POST["export"]))  
    {  
         header('Content-Type: text/csv; charset=utf-8');  
         header('Content-Disposition: attachment; filename=data.csv');  
         $output = fopen("php://output", "w");  
         fputcsv($output, array('Employee ID','Employee Name','Date','Hours Worked'));  
         ## $query = "SELECT * FROM Employees, Shifts WHERE Worker_ID like '%".$id."%'";        ---- Jason I dont think this will work in MariaDB - M
         $query = "SELECT * FROM Employees, Shifts WHERE Shifts.Worker_ID = Employees.Worker_ID && Shifts.Worker_ID = .'$id'.";
         $result = mysqli_query($link, $query);  
         while($row = mysqli_fetch_assoc($result))  
         {  
              fputcsv($output, $row);  
         }  
         fclose($output);  
    } 
    ?>
</body>
</html>