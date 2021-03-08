<!-- 
    Purpose of Script: Staff Menu Bar to be used in every staff page
    Written by: Jason Yang
    last updated: Jason 16/02/21, 17/2/21
-->
<?php
session_start();
include "ServerDetail.php"; 
     if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["Position"]) && $_SESSION["Position"] === Worker)){
    	header("location:StaffLogin.php");
    	exit;
     }
 global $link;

 $Email=$_SESSION['Email'];
 $update=false;
 $sql = "SELECT * FROM Staff WHERE Business_Email = '$Email'";
 $result = mysqli_query($link,$sql);
 if(mysqli_num_rows($result) > 0){
    $update=true;
 }
?>

<!-- Was neccesary to have the below css in this file as makes reference to topnav class 
which is created in this file and is not in scope if css
were to be included in the websiteStyle css file -->

     <style>
    /* 
       Reference : https://www.w3schools.com/howto/howto_js_topnav.asp
        Add a black background color to the top navigation 
    */
    .topnavM {
        background-color: lightseagreen;
        overflow: hidden;
    }
    
    /* Style the links inside the navigation bar */
    .topnavM a {
        float: left;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
    }
    
    /* Change the color of links on hover */
    .topnavM a:hover {
        background-color: #ddd;
        color: black;
    }
    
</style>


<div class="topnavM">
  <a href="StaffHomePage.php">Staff Home</a>
  <a href="Earnings.php">Earnings</a> 
  <a href="Tasks.php">Upcoming Tasks</a>
  <a href="Dockets.php">Get Dockets</a>
<?php if ($update){ ?>
 <a href="AddStaffInfo.php">Change Details</a>
<?php } else { ?>
  <a href="AddStaffInfo.php">Add my Info</a>
<?php } ?>
  <a href="LogOut.php">Log Out</a>
</div>
