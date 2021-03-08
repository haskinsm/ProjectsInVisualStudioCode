<!-- 
    Purpose of Script: Hours worked per employee
    Written by: Jason Yang
    last updated: 25/2/21
    Yet to add csv file, , add buttons on bottom, new entry on new line
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
    include 'ManagerMenuBar.php';?> <!-- Imports code for manager menu bar from another php file-->

    <h2> Employee Hours: </h2>
    <table>
        <tr> 

            <th> Employee ID </th>
            <th> Employee Name </th>
            <th> Date </th>
            <th> Hours Worked </th>
        
        </tr>
        <!--###Get employee hours worked where employee = option chosen from previous page, print as csv file-->
        <?php
                //Connect to SQL database
                include ("ServerDetail.php");
            
                //Get worker id from form in previous page
                $workerID = $_POST["employee_list"];

                // This will get employee ID, name
                $sql = "SELECT * FROM Employees WHERE Worker_ID like '%".$workerID."%'";
                $result = mysqli_query($link,$sql); 
              
                //Code adapted from Aideen's photo
                while($row=mysqli_fetch_assoc($result)){
                    echo '<tr>';

                    echo '<td>'.$workerID.'</td>';
                    echo '<td>'.$row["Worker_Name"].'</td>';
                   
                    $sql2Q = "SELECT * FROM Shifts WHERE Worker_ID = '$workerID' ";
                    $result2Q = mysqli_query($link,$sql2Q);

                    
                    while($row2Q=mysqli_fetch_assoc($result2Q)){
                        //get date and adjust to user friendly format
                        $datestep1 = $row2Q["Date_Of_Entry"];
                        $datestep2  = strtotime($datestep1);
                        $datefinal = date("d M Y", $datestep2); 
                        
                        $timestep1 = $row2Q["Clock_In_Time"];
                        $timestep2 = $row2Q["Clock_Out_Time"];
                        $timestep3  = strtotime($timestep1);
                        $timestep4  = strtotime($timestep2);
                        $timefinal = date("H", $timestep4 - $timestep3); 
                        
                        //We can add an if statement here to double check we don't have any negative time differences.However, this is not necessary as negative times are impossible when using clock in and out buttons. 

                        echo '<td>'.$datefinal.'</td>';
                        echo '<td>'.$timefinal.' hours</td>'; 
                        echo '<br>';
                    }
                    echo '</tr>';
                }
                //add button to return to previous page
                //add button to print .csv file
        ?>
    </table>


</body>
</html>