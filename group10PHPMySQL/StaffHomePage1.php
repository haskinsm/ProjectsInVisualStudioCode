<!-- 
     Purpose of Script: Process clock in button
    Written by: Jason Yang
    last updated: Jason 17/2/21, 18/2/21, 25/2/21
    Does not allow for session variables yet
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Home Page</title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>

    <?php 
    include 'UniversalMenuBar.php';
    echo '<br>';
    include 'StaffMenuBar.php';
    
    //Connect to SQL database
    include ("ServerDetail.php");

    //Set time for clock in
    if(isset($_POST['clock_in']))
    {
        $clock_in = date('Y-m-d H:i:s');; //round date to nearest 5 minutes, if they want to clock in and they have already clocked in, show an error and tell them they must first clock out, send notification to admin if employee has not clocked out
        $sql1 = "INSERT INTO Shifts(Worker_ID, Clock_In_Time, Clock_Out_Time) VALUES ('3','$clock_in','$clock_in')"; //change 3 to session worker ID variable
    }

    //Show clock in success message
    if($link->query($sql1)==TRUE){
        echo "Clocked in successfully.";
    } else {
        echo "ERROR: Could not execute $sql1. " . mysqli_error($link);
    }

    ?>    
</body>
</html>