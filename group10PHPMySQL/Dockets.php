<!-- 
    Purpose of Script: Show dockets
    Written by: Harry O'Brien
    last updated: 06/03/2021
    Allows employees to see their dockets
 -->

<?php
    // Start the session
    session_start();
    if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["Position"]) && $_SESSION["Position"] === Worker)){
    	header("location: StaffLogin.php");
    	exit;
     }
     include ("ServerDetail.php");
     $result=array();
     $docketDate="";
     if (isset($_POST['docketDate'])){
	$docketDate=$_POST['docketDate'];
	global $link;
	$sql = "SELECT * FROM Bookings,Customers WHERE Bookings.Business_Email=Customers.Business_Email and
		((Event_Start_Date='$docketDate' and Delivery_Or_Collection='Delivery') or 
		 (Event_End_Date='$docketDate' and Delivery_Or_Collection='Collection')) ORDER BY Event_Start_Date";
 	$dockets = mysqli_query($link,$sql);

     }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Dockets </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> <!-- All CSS should be added to the WebsiteStyle.css file and then it will be imported here. If want a unique 
            style for something should be done in line like so: E.G:   <h1 style="color:blue;text-align:center;">  This is a heading </h1>       -->
    <style>
        table
        th{
            text-align: left;
            background-color: black;
            color: #ffffff;
            padding: 10px;
        }
    </style>
</head>
<body>

    <?php include 'UniversalMenuBar.php';?> <!-- Imports code for menu bar from another php file-->

    <br>

    <?php include 'StaffMenuBar.php';?> <!-- Imports code for manager menu bar from another php file-->

    <h2> Dockets: </h2>
     <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
        <table>
            <tr>
                <td>Date:</td>
                <td> <input type="date" name="docketDate" required value="<?php echo $docketDate; ?>"> </td>           
            </tr>
            <tr>
                <td>
                    <input type="submit" name = "Submit" value = "Submit">
                </td>
            </tr>
        </table>   
    </form> 
   <?php
	if(mysqli_num_rows($dockets) > 0){ ?>
    <table>
        <tr> 

            <th> Booking ID </th>
            <th> Event Start Date </th>
            <th> Event End Date </th>
            <th> Event Start Time </th>
            <th> Event End Time </th>
            <th> Delivery/Collection </th>
            <th> Business Address </th>
            <th> Products (Qty) </th>
	    <th> Print </th>
        </tr>
        <?php
                while($row=mysqli_fetch_assoc($dockets)){
		    $bookingID = $row["Booking_ID"];
		    $sqlProducts="SELECT * FROM Products,Order_Items where Order_Items.Product_ID=Products.Product_ID
				and Order_Items.Booking_ID='$bookingID'";
		    $productsResult=mysqli_query($link,$sqlProducts);
		    $productsArray=array();
	            while($products=mysqli_fetch_assoc($productsResult)){
			$productsArray[]=$products['Product_Name'].'('.$products['Product_Qty'].')';
		    }
		    $productStr=implode(', ',$productsArray);
                    echo '<tr>';

                    
                    echo '<td>'.$bookingID.'</td>';
                    
                    $startdatestep1 = $row["Event_Start_Date"];
                    $startdatestep2  = strtotime($startdatestep1);
                    $startdatefinal = date("d M Y", $startdatestep2); 
                    echo '<td>'.$startdatefinal.'</td>';

                    $enddatestep1 = $row["Event_End_Date"];
                    $enddatestep2  = strtotime($enddatestep1);
                    $enddatefinal = date("d M Y", $enddatestep2); 
                    echo '<td>'.$enddatefinal.'</td>';
                 
                    echo '<td>'.$row["Event_Start_Time"].'</td>';
                    echo '<td>'.$row["Event_End_Time"].'</td>';
                    echo '<td>'.$row["Delivery_Or_Collection"].'</td>';
                    echo '<td>'.$row["Business_Address"].'</td>';
		    echo '<td>'.$productStr.'</td>';
		    echo '<td><a href="PrintDocket.php?booking='.$bookingID.'">Print</a></td>';

                    echo '</tr>';
                }


        ?>
    </table>
<?php } ?>

</body>
</html>