<!-- 
    Purpose of Script: Show How Much Money the employee has earned to date
    Written by: Jason Yang
    last updated: Jason 16/02/21, 1/3/21, 8/3/21
    Add session variable
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earnings Report</title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>

    <?php include 'UniversalMenuBar.php';
    echo '<br>';
    include 'StaffMenuBar.php';?>

    <h3>To date you have earned: € 
        <!--PHP script to count all members-->
        <?php
        // Start the session
        session_start();
        
        //Connect to SQL database
        include ("ServerDetail.php");
        
        //Call email session variable
        $email = $_SESSION["Email"];

        //find related worker ID
        $sqlinit = "SELECT Worker_ID FROM Employees WHERE Empl_Email = '$email';";
        $resultinit = mysqli_query($link,$sqlinit); 
        
        while($row1=mysqli_fetch_assoc($resultinit)){
            
            $workerID = $row1["Worker_ID"];
            
            $sql = "SELECT SUM(TIMESTAMPDIFF(Hour,Clock_In_Time,Clock_Out_Time))*Wage_Per_Hour AS total FROM Shifts, Employees WHERE Shifts.Worker_ID = Employees.Worker_ID AND Shifts.Worker_ID = $workerID;"; 
            $result = mysqli_query($link,$sql); 
        
            while($row2=mysqli_fetch_assoc($result)){
                $earned = $row2["total"];
                
                if ($earned > 0){
                    echo $earned;
                } else {
                    echo 0;
                }
            }
        }
        ?>
            <br> We hope you've enjoyed everything so far!
        </h3>
</body>
</html>