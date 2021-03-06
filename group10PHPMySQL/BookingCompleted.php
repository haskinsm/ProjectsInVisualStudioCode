<!-- 
    Purpose of Script: Booking confirmed page
    Written by: Michael H
    last updated: Michael 06/03/21
      Written
        
-->
<?php
    // Start the session
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed</title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>

    <?php include 'UniversalMenuBar.php';?> <!-- Imports code for menu bar from another php file-->

    <?php 
        $_SESSION["BookingComplete"] = TRUE;
        ## This will be used so the customer cannot go back to the confirm booking page without first selecting dates on the pickBookingDates page, then pick items on the MainBookingPage ...
    ?>

    <br>

    <h1>  
       Thank you for your order! 
       <br>
       Your Booking Number is: <?php echo $_SESSION["Booking_ID"]; ?>
    </h1>

    <h2> 
        Please keep a record of your Booking Number.
        <br>
        Please also note orders must be paid for before the delivery/pick-up date. Please contact Accounts Payable, 01 756 1113, <a href="mailto:accounts@dph.ie?body=" style="color: white;"> accounts@dph.ie </a> 
    </h2>



</body>
</html>