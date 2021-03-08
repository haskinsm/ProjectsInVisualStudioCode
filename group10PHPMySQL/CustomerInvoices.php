<!-- 
    Purpose of Script: Display Customer Invoices
    Written by: Harry O'Brien
    last updated: 06/03/2021
    Order items by date of order
 -->

<?php
    // Start the session
    session_start();
    if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["Position"]) && $_SESSION["Position"] === Customer)){
    	header("location: CustomerLogin.php");
    	exit;
     }
     include ("ServerDetail.php");
     $invoices=array();
    
	$Email=$_SESSION['Email'];    
	global $link;
	$sql = "SELECT * FROM Bookings,Customers WHERE Bookings.Business_Email=Customers.Business_Email and
		Customers.Business_Email='$Email' order by Date_Of_Order";
 	$invoices= mysqli_query($link,$sql);

     
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Customer Invoices </title>
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

    <?php include 'CustomerMenuBar.php';?> <!-- Imports code for customer menu bar from another php file-->

    <h2> Invoices: </h2>
       <?php
	if(mysqli_num_rows($invoices) > 0){ ?>
    <table>
        <tr> 

            <th> Booking ID </th>
            <th> Event Start Date </th>
            <th> Event End Date </th>
            <th> Event Start Time </th>
            <th> Event End Time </th>
            <th> Delivery/Collection </th>
            <th> Total Price </th>
            <th> Print </th>

        </tr>
        <?php
                while($row=mysqli_fetch_assoc($invoices)){
                    echo '<tr>';

                    $bookingID = $row["Booking_ID"];
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
                    echo '<td>'.$row["Total_Price_Incl_VAT_Del_Setup"].'</td>';
		    echo '<td><a href="PrintInvoice.php?booking='.$bookingID.'"><div class="button">Print</div></a></td>';

                    echo '</tr>';
                }


        ?>
    </table>
<?php } ?>

</body>
</html>