<!-- 
    Purpose of Script: create csv
    Written by: Jason Yang
    last updated: 6/3/21, 7/3/21
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
    ?>
    
<!--https://www.webslesson.info/2016/10/export-mysql-table-data-to-csv-file-in-php.html-->
    <div class="table-responsive" id="employee_table">  
        <table class="table table-bordered">  
            <tr>
                <th> Employee ID </th>
                <th> Employee Name </th>
                <th> Date </th>
                <th> Hours Worked </th>
            </tr>
            <?php  
            include ("ServerDetail.php");
            
            $id = $_POST["employee_list"];
            $_SESSION["id"]=$id;
            
            $query = "SELECT * FROM Employees, Shifts WHERE Employees.Worker_ID = $id AND Shifts.Worker_ID = $id";
            $result = mysqli_query($link,$query);
            
            while($row = mysqli_fetch_array($result))  
            {  
                echo '<tr>';
                    $name = $row['Worker_Name'];
                
                    $datestep1 = $row["Date_Of_Entry"];
                    $datestep2  = strtotime($datestep1);
                    $datefinal = date("d M Y", $datestep2); 
                        
                    $timestep1 = $row["Clock_In_Time"];
                    $timestep2 = $row["Clock_Out_Time"];
                    $timestep3  = strtotime($timestep1);
                    $timestep4  = strtotime($timestep2);
                    $timefinal = date("H", $timestep4 - $timestep3); 


                    echo '<td>'.$id.'</td>';
                    echo '<td>'.$name.'</td>';
                    echo '<td>'.$datefinal.'</td>';
                    echo '<td>'.$timefinal.' hours</td>'; 
                echo '<tr>';   
            }  
            ?>  
        </table>  
    </div>
    <form method='post' action='Export.php'>
        <input type="submit" name="export" value="Download as CSV" class="btn btn-success" />  
    </form> 
</body>
</html>