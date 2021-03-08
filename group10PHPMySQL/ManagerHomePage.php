<!-- 
    Purpose of Script: Manager Home page, only managers should gain access
    Written by: Michael H
    last updated: Michael 15/02/21
    Jason 4/3/21 Add Notification if staff member have not clocked out, and option to adjust worker hours
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Home Page</title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>

    <?php include 'UniversalMenuBar.php';
    echo '<br>';
    include 'ManagerMenuBar.php';?>
     
    <h2>
        Hello and welcome!
    </h2>

    <?php 
    //Connect to SQL database
    include ("ServerDetail.php");
            
    //Access the SQL database
    // This will get product ID, name, qty (stock) 
    $sql = "SELECT Shift_ID, Worker_ID FROM Shifts WHERE Clock_In_Time = Clock_Out_Time ";
    $result = mysqli_query($link,$sql); 
  
    while($row=mysqli_fetch_assoc($result)){

        $shiftID = $row["Shift_ID"];
        $workerID = $row["Worker_ID"];

        if($result > 0){
            echo "Worker $workerID is yet to clock out of their shift, shift number: $shiftID";
        } else {
            echo "Hope you have a great day!";
        }     
    }
    ?>
    <br>
    You can edit employee hours <a href= "https://stu33001.scss.tcd.ie/group_10/AdjustWorkerHours.php">here</a>
</body>
</html>