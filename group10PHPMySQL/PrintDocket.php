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
if (isset($_GET['booking'])){
    $bookingID=$_GET['booking'];
    global $link;
    $sql = "SELECT * FROM Bookings,Customers WHERE Bookings.Business_Email=Customers.Business_Email and
		Bookings.Booking_ID='$bookingID'";
    $docket = mysqli_query($link,$sql);

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
        table {
            table-layout: fixed;
            width: 70%; /* Width set at 50% of window */
            border-spacing: 0px 0px; /* First number is the gap between rows, the second is the gap between columns */
            background-color: white;
            border: 1px solid black; /* This is the black border */
        }

        td {
            text-align: left;
            color: black;
            border: 1px solid black; 
        }

        th {
	    text-align: left;
            background-color: white;
            color: black;
             border: 1px solid black; 
        }


    </style>
</head>
<body>

<?php include 'UniversalMenuBar.php';?> <!-- Imports code for menu bar from another php file-->

<br>

<?php include 'StaffMenuBar.php';?> <!-- Imports code for manager menu bar from another php file-->

<h2> Docket </h2>

<?php
if(mysqli_num_rows($docket) > 0){
    $row=mysqli_fetch_assoc($docket);
    $bookingID = $row["Booking_ID"];
    $startdatestep1 = $row["Event_Start_Date"];
    $startdatestep2  = strtotime($startdatestep1);
    $startdatefinal = date("d M Y", $startdatestep2);
    $enddatestep1 = $row["Event_End_Date"];
    $enddatestep2  = strtotime($enddatestep1);
    $enddatefinal = date("d M Y", $enddatestep2);
    $sqlProducts="SELECT * FROM Products,Order_Items where Order_Items.Product_ID=Products.Product_ID
				and Order_Items.Booking_ID='$bookingID'";
    $productsResult=mysqli_query($link,$sqlProducts);

   ?>
    <table>
        <tr>
            <th> Booking ID </th>
            <td><?php echo $bookingID?></td>
        </tr>
        <tr>
            <th> Event Start Date </th>
            <td><?php echo $startdatefinal ?></td>
        </tr>
        <tr>
            <th> Event End Date </th>
            <td><?php echo $enddatefinal ?></td>
        </tr>
        <tr>
            <th> Event Start Time </th>
            <td><?php echo $row["Event_Start_Time"] ?></td>
        </tr>
        <tr>
            <th> Event End Time </th>
            <td><?php echo $row["Event_End_Time"] ?></td>
        </tr>
        <tr>
            <th> Delivery/Collection </th>
            <td><?php echo $row["Delivery_Or_Collection"] ?></td>
        </tr>
        <tr>
            <th> Business Address </th>
            <td><?php echo $row["Business_Address"] ?></td>
        </tr>
       
    </table>
    <table>
	 <tr>
            <th> Products</th>
            <th> Quantity </th>
        </tr>
        <?php
        while($products=mysqli_fetch_assoc($productsResult)){
            echo '<tr>';
            echo '<td>'.$products['Product_Name'].'</td>';

            echo '<td>'.$products['Product_Qty'].'</td>';
            echo '</tr>';
        }


        ?>
    </table>
<button onClick="window.print()">Print this page</button>
<?php } ?>

</body>
</html>