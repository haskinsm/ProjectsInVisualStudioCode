<!--
    Purpose of Script: Allow staff to upload their name and wage per hour
    Written by: Harry O'Brien
    last updated: Harry 08/03/21
    Source for Info form: CustomerInfoCode.php -->


<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["Position"]) && $_SESSION["Position"] === Worker)){
    header("location: StaffLogin.php");
    exit;
}
 

include "ServerDetail.php";
if(isset($_POST['addStaffInfoBtn'])){

    global $link;

    $name     = $_POST['name']; 
    $wage     = $_POST['wage'];
    $email    = $_SESSION['Email'];
   
    
    $queryEmail         = "SELECT * FROM Employees WHERE Empl_Email = '$email' LIMIT 1";
    $queryEmailResult   = mysqli_query($link , $queryEmail); 
    if(mysqli_num_rows($queryEmailResult) > 0){
        header("Location: AddStaffInfo.php?emailExist");
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
        header("Location: StaffHomePage.php?successJoining&name=$name");
    }
    

}

if(isset($_POST['updateStaffInfoBtn'])){

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
        header("Location: StaffHomePage.php?successJoining&name=$name");
    }
    
}



?>