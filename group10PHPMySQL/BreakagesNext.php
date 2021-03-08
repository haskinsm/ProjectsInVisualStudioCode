<!-- 
    Purpose of Script: Track Breakages confirmation page
    Written by: Jason Yang
    last updated: Jason 2/3/21
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Breakages </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>

        <?php include 'UniversalMenuBar.php';
        echo '<br>';
        include 'ManagerMenuBar.php';

        include ("ServerDetail.php"); 
        
        //Set variables from previous page
        $booking_id = $_POST["booking_id"];
        $business_id = $_POST["business_id"];
        $product_id = $_POST["product_id"];
        $breakage_qty = $_POST["breakage_qty"];

        //Allow special character input
        //Code adapted from https://stackoverflow.com/questions/2584066/how-to-insert-special-characters-into-a-database
        $booking_id=mysqli_real_escape_string($link,$booking_id);
       
        
        //Checks to see if booking ID is entered
        if(!$booking_id)
        {
            echo 'Please enter the related booking ID.';
            exit;
        }

        //Checks to see if related business ID is entered
        if(!$business_id)
        {
            echo 'Please enter the related business ID.';
            exit;
        } 

        //Checks to see if product ID is entered
        if(!$product_id)
        {
            echo 'Please enter the related product ID.';
            exit;
        }

        //Checks to see if breakage quantity is entered
        if(!$breakage_qty)
        {
            echo 'Please enter the quantity of items broken.';
            exit;
        }

        //Insert all values into SQL database
        $q  = "INSERT INTO Breakages ("; 
        $q .= "Booking_ID, Business_ID, Product_ID, Breakage_Qty";
        $q .= ") VALUES (";
        $q .= "'$booking_id', '$business_id', '$product_id', '$breakage_qty')"; 

        $result = $link->query($q);   
        
        ?>
        <h2>Breakage tracked successfully!</h2>
    </body> 
</html>
