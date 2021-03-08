<!-- 
    Purpose of Script: Edit Employee data
    Written by: Jason Yang
    last updated: Jason 4/3/21
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
    <title> Edit Employee Data </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>

    <?php include 'UniversalMenuBar.php';
    echo '<br>';
    include 'ManagerMenuBar.php';?> 

<h4>Select Employee: </h4>
        <!--Goes to EmployeeDataNext.php page if details correctly entered-->
        <form action="EmployeeDataNext.php" method="post" name="signupform" id="signupform">            
            <table>
            <tr>  
            <td>
            <!--This select statement stores the users choice-->
            <select name="employee_list" id="employee_list">
                <?php
                //Connect to SQL database
                include ("ServerDetail.php");
        
                //Access the SQL database
                $sql = "SELECT * FROM Employees;";
                $result = mysqli_query($link,$sql); 

                //Code adapted from Aideen's photo, thank you Aideen!
                while($row=mysqli_fetch_assoc($result)){
                    echo "<option value = '{$row['Worker_ID']}'>{$row['Worker_Name']}</option>";
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