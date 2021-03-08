<!-- 
    Purpose of Script: Staff Home page, only ordinary staff should gain access, gives button for employees to clock in or out
    Written by: Jason Yang
    last updated: Jason 16/02/21
    17/2/21
    25/2/21
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
    <!-- All CSS should be added to the WebsiteStyle.css file and then it will be imported here. 
    If we want a unique style for something should be done in line like so: E.G:   
    <h1 style="color:blue;text-align:center;">  This is a heading </h1>       -->
</head>
<body>

    <?php 
    include 'UniversalMenuBar.php'; 
    echo '<br>';
    include 'StaffMenuBar.php';
    ?> 
    
    <!--Button for employee to clock in -->
    <form action="StaffHomePage1.php" method="POST">
        <button name="clock_in" class="click">Clock In</button>
    </form>

    <!--//Button for employee to clock out-->
    <form action="StaffHomePage2.php" method="POST">
        <button name="clock_out" class="click">Clock Out</button>
    </form>
</body>
</html>