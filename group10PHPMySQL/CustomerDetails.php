<!-- 
    Purpose of Script: Customer can view their details here, and update them
    Written by: Harry O'Brien
    last updated: Harry 22/2/21
    
-->
<?php
 session_start();
     include "ServerDetail.php"; 
     if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["Position"]) && $_SESSION["Position"] === Customer)){
    	header("location: CustomerLogin.php");
    	exit;
     }

     global $link;

     $Email=$_SESSION['Email'];
     $businessAddress="";
     $businessName="";
     $eircode="";
     $businessPhone="";
     $showDetail=false;
     $sql = "SELECT * FROM Customers WHERE Business_Email = '$Email'";
     $result = mysqli_query($link,$sql);
     if(mysqli_num_rows($result) > 0){
	$showDetail=true;
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	$businessAddress=$row['Business_Address'];
	$businessName=$row['Business_Name'];
	$eircode=$row['Eircode'];
	$businessPhone=$row['Business_Phone'];

    }
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
    <?php if ($showDetail){ ?>

	   
                 <h3 class="text-success">
                        Customer Details<br>
                 </h3>
                 <table>
		    <tr>
                        <th>Business Name:
                        </th>
                        <td>
                            <?php echo $businessName; ?>
                        </td>
                    </tr>
		    <tr>
                        <th>Business Address:
                        </th>
                        <td>
                            <?php echo $businessAddress; ?>
                        </td>
                    </tr>
		    <tr>
                        <th>Eircode:
                        </th>
                        <td>
                            <?php echo $eircode; ?>
                        </td>
                    </tr>
	  	    <tr>
                        <th>Phone Number: 00 353
                        </th>
                        <td>
                            <?php echo $businessPhone; ?>
                        </td>
                    </tr>
		</table>
     <?php } else { ?>
	<a href="AddCustomerInfo.php">Please add your customer details</a>
    <?php } ?>
<br>
 <a href="CustomerLogOut.php" class="btn btn-danger">Sign Out of your Account</a>
    </body>
</html>