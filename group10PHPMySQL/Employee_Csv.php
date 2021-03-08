<!-- 
    Purpose of Script: Hours worked per employee
    Written by: Jason Yang
    last updated: 25/2/21
    This page doesn't work
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
    ?>
    
    <!--Reference:https://makitweb.com/how-to-export-mysql-table-data-as-csv-file-in-php/ -->
    <div class="container">
    
    <form method='post' action='Download.php'>
    
    <table>
        <tr>
        <th> Employee ID </th>
        <th> Employee Name </th>
        <th> Date </th>
        <th> Hours Worked </th>
        </tr>
        <?php 
        $query = "SELECT * FROM Employees, Shifts WHERE Worker_ID like '%".$workerID."%'";
        $result = mysqli_query($link,$query);
        $user_arr = array();

        while($row1 = mysqli_fetch_array($result1)){
            $id = $_POST["employee_list"];
            $name = $row['Worker_Name'];
            
            $datestep1 = $row2["Date_Of_Entry"];
            $datestep2  = strtotime($datestep1);
            $datefinal = date("d M Y", $datestep2); 
            
            $timestep1 = $row2["Clock_In_Time"];
            $timestep2 = $row2["Clock_Out_Time"];
            $timestep3  = strtotime($timestep1);
            $timestep4  = strtotime($timestep2);
            $timefinal = date("H", $timestep4 - $timestep3); 

            echo '<td>'.$id.'</td>';
            echo '<td>'.$name.'</td>';
            echo '<td>'.$datefinal.'</td>';
            echo '<td>'.$timefinal.' hours</td>';
            echo '<br>';

            $user_arr[] = array($id,$name,$datefinal,$timefinal);
        }
        ?>
    </table>
    <input type='submit' value='Export' name='Download as .csv file'>
</body>
</html>