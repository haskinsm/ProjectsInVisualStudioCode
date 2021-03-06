<!-- 
    Purpose of Script: Hours worked per employee
    Written by: Jason Yang
    last updated: Jason 20/2/21, 25/2/21
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

    <h2> Employee Hours Summary: </h2>
    <table>
        <tr> 

            <th> Employee ID </th>
            <th> Employee Name </th>
            <th> Hours Worked </th>
        
        </tr>
        <?php
                //Connect to SQL database
                include ("ServerDetail.php");
            
                //Access the SQL database
                // This will get employee ID, name
                $sql1 = "SELECT Worker_ID, Worker_Name FROM Employees ";
                $result1 = mysqli_query($link,$sql1); 
              
                $counter = 0;
                while($row1=mysqli_fetch_assoc($result1)){
                    echo '<tr>';

                    
                    $workerID[$counter] = $row1["Worker_ID"];
                    $workerName[$counter] = $row1["Worker_Name"];

                    echo '<td>'.$workerID[$counter].'</td>';
                    echo '<td>'.$workerName[$counter].'</td>';
                   
                    // This will get the number of hours each employee has worked
                    $sql2 = "SELECT count(Worker_ID), sum(TIMESTAMPDIFF(Hour,Clock_In_Time,Clock_Out_Time)) FROM Shifts WHERE Worker_ID = '$workerID[$counter]' ";
                    $result2 = mysqli_query($link,$sql2); 

                    while($row2=mysqli_fetch_assoc($result2)){
                        $numHours = $row2["count(Worker_ID)"]; 
                        $hoursWorked = $row2["sum(TIMESTAMPDIFF(Hour,Clock_In_Time,Clock_Out_Time))"];

                        if( $numHours == 0){
                            echo  '<td>0 hours</td>';
                        } else {
                            //We can add an if statement here to double check we don't have any negative time differences.
                            //However, this is not necessary as negative times are impossible when using clock in and out buttons
                            echo '<td>'.$hoursWorked.' hours</td>'; 
                        }
                    }

                    echo '</tr>';
                }


        ?>
    </table>

    <h2> Choose Employee to get detailed hours: </h2>
    <!--Goes to CreateCSV.php page if details correctly entered-->
    <form action="CreateCSV.php" method="post" name="signupform" id="signupform">            
            <table>
            <tr>  
            <td>
            <!--This select statement stores the users choice-->
            <select name="employee_list" id="employee_list">
                <?php
                //Connect to SQL database
                include ("ServerDetail.php");
        
                //Access the SQL database
                $sql3 = "SELECT * FROM Employees;";
                $result3 = mysqli_query($link,$sql3); 

                //Code adapted from Aideen's photo, thank you Aideen!
                while($row3=mysqli_fetch_assoc($result3)){
                    echo "<option value = '{$row3['Worker_ID']}'>{$row3['Worker_Name']}</option>";
                }
                ?>
                </select>
                <input type="submit" value="Submit">
            </tr>
            </table>
    </form>



</body>
</html>