<!-- 
    Purpose of Script: Customer Home page, where they can look at their bookings & make a booking
    Written by: Harry O'Brien
    last updated: Harry 20/2/21
    Does not allow for session variables yet
-->
<?php
// Initialize the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Home Page</title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
    <!-- All CSS should be added to the WebsiteStyle.css file and then it will be imported here. 
    If we want a unique style for something should be done in line like so: E.G:   
    <h1 style="color:blue;text-align:center;">  This is a heading </h1>       -->
<style type="text/css">
body {
color: white;
font: 20px sans-serif;
}
</style>
</head>
<body>

    <?php include 'UniversalMenuBar.php';?> <!-- Imports code for menu bar from another php file-->

    <br>

    <?php include 'CustomerMenuBar.php';?> <!-- Imports code for customer menu bar from another php file-->
    <br>
	<h2>	  Hi, <?php echo $_SESSION['Firstname']; ?>! I hope you're well. </h2>
	<br>
<br>
    </body>
</html>