<!-- 
    Purpose of Script: Dropdown List of Employees
    Written by: Jason Yang
    last updated: Jason 16/02/21
-->
<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!--This is the Head section-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Choose Employee Page</title>
    </head>
    <body>
        <img src="logo.png" alt="logo" height="100" width="220">
        <!--This gives the user the option to view all events in a new tab i.e. access a report page-->
        <h4>You can view a full list of our employees <a href="all_employees.php" target="_blank">here</a></h4>
        <h4>Select employee: </h4>
        <!--Goes to select_employee_success.php page if details correctly entered-->
        <form action="select_employee_success.php" method="post" name="signupform" id="signupform">            
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
                    echo "<option value = '{$row['Worker ID']}'>{$row['Worker Name']}</option>";
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
        <h5>Change your mind?</h5>
        <button><a href="member_page.php"> Back to Members' Area </a></button>
    </body> 
</html>
