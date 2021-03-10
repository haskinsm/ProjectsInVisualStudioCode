<!--
    Purpose of Script: Insert MAnager Info into Managers table
    Written by: Harry O'Brien
    last updated: Harry 06/03/21
    Source for Info form: CustomerInfoCode.php -->


<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["Position"]) && $_SESSION["Position"] === Manager)){
    header("location: ManagementLogin.php");
    exit;
}

include "ServerDetail.php";

if(isset($_POST['addManagerInfoBtn'])){

    global $link;

    $name     = $_POST['name']; 
    $wage     = $_POST['wage'];
    $email    = $_SESSION['Email'];
   
    
    $queryEmail         = "SELECT * FROM Employees WHERE Empl_Email = '$email' LIMIT 1";
    $queryEmailResult   = mysqli_query($link , $queryEmail); 
    if(mysqli_num_rows($queryEmailResult) > 0){
        header("Location: AddManagerInfo.php?emailExist");
        exit;
    }

    $query =   "INSERT INTO Employees set 
                Worker_Name    	= '".str_replace("'", "\'", $name)."', 
                Wage_Per_Hour    	= '$wage',
                Empl_Email     	= '$email'";
                 

    $Result = mysqli_query($link , $query);

    
    if(isset($_SESSION['id']) && $_SESSION['isOfficer'] == 1){
        header("Location: index.php?successJoining&name=$name");
    }
    else
    {
        header("Location: ManagerHomePage.php?successJoining&name=$name");
    }
    

}

if(isset($_POST['updateManagerInfoBtn'])){

    global $link;

    $name     = $_POST['name'];
    $wage     = $_POST['wage'];
    $email    = $_SESSION['Email'];
   
  

    $query =   "UPDATE Employees set 
                Worker_Name    	= '".str_replace("'", "\'", $name)."', 
                Wage_Per_Hour    	= '$wage'
		where Empl_Email     	= '$email'";
             

    $Result = mysqli_query($link , $query);

    
    if(isset($_SESSION['id']) && $_SESSION['isOfficer'] == 1){
        header("Location: index.php?successJoining&name=$name");
    }
    else
    {
        header("Location: ManagerHomePage.php?successJoining&name=$name");
    }
    

    
    

}

?>