<!-- 
    Purpose of Script: Staff Ability to Log Hours
    Written by: Jason Yang
    last updated: Jason 16/02/21
    Yet to add session variable and validation check for whether endtime is later than starttime
    Also need to think of something with regards to entering the data i.e. we will probably need a drop down where they can choose if they want to clock in or clock out and then choose their time. In other words, they will not be clocking in and out at the same time
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Hours</title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
    <!-- All CSS should be added to the WebsiteStyle.css file and then it will be imported here. 
    If we want a unique style for something should be done in line like so: E.G:   
    <h1 style="color:blue;text-align:center;">  This is a heading </h1>       -->
</head>
<body>

    <?php include 'UniversalMenuBar.php';?> <!-- Imports code for menu bar from another php file-->

    <br>

    <?php include 'StaffMenuBar.php';?> <!-- Imports code for manager menu bar from another php file-->
        
        <html>
    <!--Create Form ###########This form should check whether end time is after start time###########-->
    <form action="LogHours.php" method="post" name="signupform" id="signupform">
            <label for="StartTime">Start Time:</label>
            <input type="time" name="StartTime" id="StartTime" min="8:00" max ="18:00">
            <br><br>
            <label for="EndTime">End Time:</label>
            <input type="time" name="EndTime" id="EndTime" min="8:00" max="18:00">
            <h4><b>Enter information and click</b></h4>
            <p><input type="submit" value="Submit">  
        </html>

    <?php
    //Connect to SQL database
    include ("ServerDetail.php");
        
    //Access the SQL database
    $sql = "SELECT * FROM Employees, Shifts"; 
    $result = mysqli_query($link,$sql); 
        
    //Student number should be a saved session variable from the homepage
    //$Worker_ID = $_SESSION['User_ID']; //Session variable
    $Clock_In_Time = $_POST["StartTime"];
    $Clock_Out_Time = $_POST["EndTime"];

    //Insert all values into SQL database
    $q  = "INSERT INTO Shifts ("; 
    $q .= "StartTime, EndTime";
    $q .= ") VALUES (";
    $q .= "'$Clock_In_Time', '$Clock_Out_Time')"; 

    $result = $db->query($q);   

    echo $q;
    //Go to LogHoursSuccess page
    //header('Location: LogHoursSuccess.php');
    ?>
    </body> 
</html>
