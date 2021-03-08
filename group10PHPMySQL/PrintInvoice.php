<!--
    Purpose of Script: Display Invoice in order to print
    Written by: Harry O'Brien
    last updated: 08/03/2021
-->

<?php
// Start the session
session_start();
 if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["Position"]) && $_SESSION["Position"] === Customer)){
    	header("location: CustomerLogin.php");
    	exit;
     }
$Email=$_SESSION['Email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Print Invoice </title>
    <link rel="stylesheet" href="WebsiteStyle.css">
    <!-- Now Overriding styling from the WebsiteStyle.CSS sheet and adding styling to tables as want tables to have same width & the text to allign centrally & borders etc  -->
    <style>
        table {
            table-layout: fixed;
            width: 80%; /* Width set at 50% of window */
            border-spacing: 0px 0px; /* First number is the gap between rows, the second is the gap between columns */
            background-color: white;
            border: 1px solid black; /* This is the black border */
        }

        td {
            width: 16%;
            text-align: center;
            color: black;
        }

        th {
            background-color: black;
            color: white;
        }


    </style>

</head>
<body>

<?php include 'UniversalMenuBar.php'; ?> <!-- Imports code for menu bar from another php file-->

 <br>

    <?php include 'CustomerMenuBar.php';?> <!-- Imports code for customer menu bar from another php file-->


<?php

## Take in invoice booking id from GET param
$booking_ID = isset($_GET["booking"]) ? $_GET["booking"] : "";

## Get booking data
// Connect to SQL database
include("ServerDetail.php");

// Access the SQL database
// This will get Flat delivery Fare (for Dublin), the additional Delivery Fee and the Vat Rate
$sql = "SELECT Flat_Delivery_Fee, Additional_Delivery_Fee, VAT_Rate FROM Other_Data ";
$result = mysqli_query($link, $sql);

$row = mysqli_fetch_assoc($result);
$dublinDelivRate = $row["Flat_Delivery_Fee"];
$delivChargePerKm = $row["Additional_Delivery_Fee"];
$VATRate = $row["VAT_Rate"]; ## This will be used when outputting VAT figure to table


## ****No data for distance
$kmDistFromDublin = 0;
$deliveryCost = $dublinDelivRate + $kmDistFromDublin * $delivChargePerKm;  ## If Dublin: deliveryCost = 75 + 0*(0.50) = 75. Note these numbers can for deliv charges can be changed in the DB

$sql = "SELECT * FROM Bookings where Booking_ID='$booking_ID' and Business_Email='$Email'";

$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {

    $row = mysqli_fetch_assoc($result);

    $totalPrice = $row['Total_Price_Incl_VAT_Del_Setup'];
    $startDate=$row['Event_Start_Date'];
    $endDate=$row['Event_End_Date'];
    $sqlQ3 = "SELECT * FROM Order_Items,Products WHERE Order_Items.Product_ID=Products.Product_ID and Booking_ID='$booking_ID'";

    $resultQ3 = mysqli_query($link, $sqlQ3);
}
else {
 echo "No Invoice found";
 exit;
}

?>

<h2> Invoice </h2>

<table>
    <tr>
        <th> Quantity</th>
        <th> Description</th>
        <th> Unit Price per rental period (Incl. any discounts)</th>
        <th> Rental Periods</th>
        <th> Total</th>
    </tr>
    <?php
    ## Create a Loop to calcualte setup cost and output stuff to the table
    $billingPeriods = $_SESSION["num48hrPeriods"];
    $totalSetupCost = 0.00;
    $subtotal = 0.00;
    while ($row = mysqli_fetch_assoc($resultQ3)) {
        $qty = $row['Product_Qty'];

        if ($qty >= 1) {
            echo '<tr>';
            echo '<td style="border: 1px solid black;">' . $qty . '</td>'; ## Styling Adds border around data

            $prodName = $row["Product_Name"];
            $prodSetupCost = $row["Setup_Cost"];
            $prodPrice = $row["Rental_Fee"];
	 
            $start = strtotime($startDate);
            $end  = strtotime($endDate);
            $diffBetweenDates = ceil(abs($end - $start) / 86400);  ## Note the ceil function rounds up and abs gets the absolute value
            $mod = ( $diffBetweenDates%2 ); ## Should only ever be 1 or 0. Eg: (2)%2=0, (3)%2=1 
            ## Floor is used to round down to nearest integer. Eg: floor(5/2) => floor(2.5) = 2
            $billingPeriods = ( floor($diffBetweenDates/2) + $mod ); ## If differnce between dates is 5, should result in 2 + 1 = 3 '48hr' periods

            $prodPrice = number_format((float)($prodPrice), 2, '.', ''); ## Make sure price has is to two decimal places

            echo '<td style="border: 1px solid black;">' . $prodName . '</td>'; ## Styling Adds border around data
            echo '<td style="border: 1px solid black;"> €' . $prodPrice . '</td>';
            echo '<td style="border: 1px solid black;">' . $billingPeriods . '</td>';

            ## Now output row total to table
            ## Below code will ensure 7 becomes 7.00 and 8.123 => 8.12 and 7.589 => 7.59. Note it is now a string    src: https://stackoverflow.com/questions/4483540/show-a-number-to-two-decimal-places
            $rowTotal = number_format((float)($qty * $prodPrice * $billingPeriods), 2, '.', ',');
            echo '<td style="border: 1px solid black;">€' . $rowTotal . '</td>';

            ## Calcl total setup cost below, ignoring values which are 'N\a' as these have no setup cost
            if ($prodSetupCost != "N/a") {
                $totalSetupCost = $totalSetupCost + $qty * ($prodSetupCost);
            }

            ## Calc subtotal
            $subtotal = number_format((float)($subtotal + $rowTotal), 2, '.', ','); ## Rounds to two places so 8-> 8.00 etc
            echo '</tr>';
        }
    }
    $VAT = number_format((float)($subtotal * $VATRate), 2, '.', ','); ## Rounds to two places so 8-> 8.00 etc
    $totalDueBeforeDeliv = number_format((float)($subtotal + $VAT), 2, '.', ','); ## Rounds to two places so 8-> 8.00 etc


    ?>
</table>
<table>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td> Subtotal (Excl. Deliv & Setup)</td>
        <td style="border: 1px solid black;"> €<?php echo $subtotal ?> </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td> VAT at <?php echo($VATRate * 100) ?>%</td>
        <td style="border: 1px solid black;"> €<?php echo $VAT ?>  </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td> Total Due (Excl. Deliv & Setup)</td>
        <td style="border: 1px solid black;"> €<?php echo $totalDueBeforeDeliv ?> </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td> Total Due (Inc. Deliv & Setup)</td>
        <td style="border: 1px solid black;"> €<?php echo $totalPrice ?> </td>
    </tr>
</table>
<button onClick="window.print()">Print this page</button>

</form>


</body>


</html>