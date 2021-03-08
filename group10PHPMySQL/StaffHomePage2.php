<!-- 
    Purpose of Script: Process clock out button
    Written by: Jason Yang
    last updated: Jason 17/2/21, 1/3/21, 2/3/21, 3/3/21
    Does not allow for session variables yet
    Finally works!
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

    <?php include 'UniversalMenuBar.php';

    echo '<br>';

    include 'StaffMenuBar.php';
    
    //Connect to SQL database
    include ("ServerDetail.php");
    
    //Set time for clock out
    if(isset($_POST['clock_out']))
    {
        $clock_out = date('H:i:s');; 
        
        //Find existing unclosed
        $unclosed_record = mysqli_query($link,"SELECT * FROM Shifts WHERE Clock_In_Time = Clock_Out_Time AND Worker_ID = 3");//change 3 to session worker ID variable


        //something is wrong here. I think it's the if statement. The SQL works in SQL
       if($unclosed_record){
            $sql2 = mysqli_query($link, "UPDATE Shifts SET Clock_Out_Time = '".$clock_out."' WHERE Clock_In_Time = Clock_Out_Time AND Worker_ID = 3"); //change 3 to session worker ID variable

            //Show clock out success message
            echo "Clocked out successfully!";

        }else{
            echo "Please clock in first";
            exit;
        }
    }
    
    ?>
       
</body>
</html>