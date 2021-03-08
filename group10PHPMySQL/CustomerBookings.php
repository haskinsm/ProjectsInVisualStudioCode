<!-- 
    Purpose of Script: Page where customers can see their bookings
    Written by: Harry O'Brien
    last updated: Harry 22/2/21
    Does not allow for session variables yet
-->
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
	<h2>	  This part of the page is currently under construction. Here, customers will be able to
 view all the booking they have made.
			 </h2>
	<br>
<br>
 <a href="CustomerLogOut.php" class="btn btn-danger">Sign Out of your Account</a>
    </body>
</html>