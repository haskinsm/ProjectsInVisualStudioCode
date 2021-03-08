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
        include 'ManagerMenuBar.php';?>

        

        <h4>Select Date to Change: </h4>
        <!--Goes to AdjustWorkerHoursNext.php page if details correctly entered-->
        <form action="AdjustWorkerHoursNext.php" method="post" name="signupform" id="signupform">            
            <table>
            <tr>  
            <td>
            <!--This select statement stores the users choice-->
            <select name="date_list" id="date_list">
                <?php
                //Connect to SQL database
                include ("ServerDetail.php");

                //Set variables from previous page
                $workerID = $_POST["employee_list"];

                //Set a session variable for worker ID for the next page
                $_SESSION["workerID"]=$workerID;
        
                //Access the SQL database
                $sql = "SELECT * FROM Shifts WHERE Worker_ID = $workerID;";
                $result = mysqli_query($link,$sql); 

                //Code adapted from Aideen's photo, thank you Aideen!
                while($row=mysqli_fetch_assoc($result)){

                    //get date and adjust to user friendly format
                    $datestep1 = $row["Date_Of_Entry"];
                    $datestep2  = strtotime($datestep1);
                    $datefinal = date("d M Y", $datestep2); 

                    echo "<option value = '{$row['Shift_ID']}'>{$datefinal}</option>";
                }
                ?>
                </select>
            </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                <input type="submit" value="Submit">
            </tr>
            </table>
        </form>
    </body> 
</html>
