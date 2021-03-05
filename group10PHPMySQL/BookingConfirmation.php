<!-- 
    Purpose of Script: Users should only be broought here once they complete the MainBookingForm page. The avaialable qty with respect to their dates will be shown here
    Written by: Michael H
    last updated: Michael 03/03/21
                Written as much as I can until Harry does the Guest Logins; Ordered items are displayed, vat is shown and have a drop down bar that works for deliv and set up costs.
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
    <title> Booking Confirmation Page </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
    <!-- Now Overriding styling from the WebsiteStyle.CSS sheet and adding styling to tables as want tables to have same width & the text to allign centrally & borders etc  -->
    <style>
        table {
            table-layout: fixed ;
            width: 50% ; /* Width set at 50% of window */
            border-spacing: 0px 0px;  /* First number is the gap between rows, the second is the gap between columns */
            background-color: white;
            border: 1px solid black; /* This is the black border */
        }
        td {
            width: 10% ;
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

    <?php include 'UniversalMenuBar.php';?> <!-- Imports code for menu bar from another php file-->

    <br> 

    <h2> Booking Confirmation </h2>

    <?php 

        ## Take in dates stored as session variables and output to user
        $startDate = $_SESSION["startDate"];
        $endDate = $_SESSION["endDate"];
        ## Now display start and end dates selceted to user 
        echo '<h3> Start date selected:  '.date('d-m-Y', strtotime($startDate) ).'. <br> End date selected:  '.date('d-m-Y', strtotime($endDate) ).'. </h3>'; 
        echo '<h3> Please note your order must be paid for before '.date('d-m-Y', strtotime($startDate) ).'. Please contact Accounts Payable, 01 756 1113, <a href="mailto:accounts@dph.ie?body=" style="color: white;"> accounts@dph.ie </a> </h3>'; 
        ## Code above also converts the dates back to more common d-m-Y format

        ## Will now write a query to get the current VAT, delivery rate for dublin and deliv charge per km outside of dublin
        // Connect to SQL database
        include ("ServerDetail.php");
            
        // Access the SQL database
        // This will get Flat delivery Fare (for Dublin), the additional Delivery Fee and the Vat Rate 
        $sql = "SELECT Flat_Delivery_Fee, Additional_Delivery_Fee, VAT_Rate FROM Other_Data ";
        $result = mysqli_query($link,$sql); 
       
        $row = mysqli_fetch_assoc($result);
        $dublinDelivRate = $row["Flat_Delivery_Fee"];
        $delivChargePerKm = $row["Additional_Delivery_Fee"];
        $VATRate = $row["VAT_Rate"]; ## This will be used when outputting VAT figure to table
        

        ## Take in km distance outside dublin from session variable and then calculate Delivery Cost
        $kmDistFromDublin = $_SESSION["kmOutsideDublin"];
        $deliveryCost = $dublinDelivRate + $kmDistFromDublin*$delivChargePerKm;  ## If Dublin: deliveryCost = 75 + 0*(0.50) = 75. Note these numbers can for deliv charges can be changed in the DB

        $dataEnteredCorrectly = FALSE;
        // Will enter here once submit has been hit
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $specialInstr = "";
            $delivSetupPickUp = "";

            $delivSetupPickUp = $_POST["delivOrColl"];
            $specialInstr = $_POST["specInstr"];

            ## Prepare variables for insertion into bookings table
            $deliveryStatus = "N/a";
            $collStatus = "Not Collected"; ##Collection field doubles up as Collection of delivered items and collection of pick ups
            $returnStatus = "N\a";
            $setup = "N\a";

            if( $delivSetupPickUp == "ClickCollect"){
                $returnStatus = "Not Returned";
            } elseif( $delivSetupPickUp == "DeliverySetup" ){
                $setup = "Not set-up";
                $deliveryStatus = "Not Delivered";
            } else{ ## Delivery Only
                $deliveryStatus = "Not Delivered";
            }

             ## Include database connect file
             require_once "ServerDetail.php"; ## This will connect to db

             ## Now Access the SQL database 
             ## This query will 
             $sql = "";
             $result = mysqli_query($link,$sql); 

             $prodCount = 0; ## Will be set to qty of products displayed. Need this to help with naming the relevant session variables

             while($row = mysqli_fetch_assoc($result) ){
             }

        }
    ?>

    <h2> Basket: </h2>

    <table>
        <tr>
            <th> Quantity </th>
            <th> Description </th>
            <th> Unit Price per rental period (Incl. any discounts) </th>
            <th> Rental Periods </th>
            <th> Total </th>
        </tr>
        <?php
            ## Create a Loop to calcualte setup cost and output stuff to the table
            $billingPeriods = $_SESSION["num48hrPeriods"];
            $totalSetupCost = 0.00;
            $subtotal = 0.00;
            for($x = 1; $x <= $_SESSION["prodCount"]; $x++){
                $qty = $_SESSION["product".$x."qty"]; ## For 1st product sesssion var for setup is called "prod1Setup"

                ## Only output if qty greater than or equal to 1
                if($qty >= 1){
                    echo '<tr>';
                        echo '<td style="border: 1px solid black;">'.$qty.'</td>'; ## Styling Adds border around data 

                        ## take in remaining info on product from session vars
                        $prodName =  $_SESSION["prod".$x."Name"]; ## For 1st product sesssion var for name is called "prod1Name"
                        $prodSetupCost = $_SESSION["prod".$x."Setup"];
                        $prodPrice = $_SESSION["prod".$x."Price"];

                        $prodPrice = number_format( (float)($prodPrice), 2, '.', '' ); ## Make sure price has is to two decimal places

                        echo '<td style="border: 1px solid black;">'.$prodName.'</td>'; ## Styling Adds border around data 
                        echo '<td style="border: 1px solid black;"> €'.$prodPrice.'</td>';
                        echo '<td style="border: 1px solid black;">'.$billingPeriods.'</td>';

                        ## Now output row total to table
                        ## Below code will ensure 7 becomes 7.00 and 8.123 => 8.12 and 7.589 => 7.59. Note it is now a string    src: https://stackoverflow.com/questions/4483540/show-a-number-to-two-decimal-places 
                        $rowTotal = number_format( (float)($qty*$prodPrice*$billingPeriods), 2, '.', '' );
                        echo '<td style="border: 1px solid black;">€'.$rowTotal.'</td>';
                    
                        ## Calcl total setup cost below, ignoring values which are 'N\a' as these have no setup cost
                        if( $prodSetupCost != "N/a" ){
                            $totalSetupCost = $totalSetupCost + $qty*($prodSetupCost);
                        }

                        ## Calc subtotal
                        $subtotal = number_format( (float)( $subtotal + $rowTotal ), 2, '.', '' ); ## Rounds to two places so 8-> 8.00 etc
                     echo '</tr>';
                }
            }
            $VAT = number_format( (float)( $subtotal*$VATRate ), 2, '.', '' ); ## Rounds to two places so 8-> 8.00 etc
            $totalDueBeforeDeliv = number_format( (float)( $subtotal + $VAT ), 2, '.', '' ); ## Rounds to two places so 8-> 8.00 etc
        
        ?>
    </table>
    <table>
        <tr>
            <td> </td>
            <td></td>
            <td> </td>
            <td> Subtotal (Excl. Deliv & Setup) </td>
            <td style="border: 1px solid black;"> €<?php echo $subtotal ?> </td> 
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td> VAT at <?php echo ($VATRate*100) ?>% </td>
            <td style="border: 1px solid black;"> €<?php echo $VAT ?>  </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td> Total Due (Excl. Deliv & Setup) </td>
            <td style="border: 1px solid black;"> €<?php echo $totalDueBeforeDeliv ?> </td>
        </tr>
    </table>


    <!-- Now redirect user to following page if data has been entered -->
    <script language="javascript">	
        // Will enter below condition if dates have been submitted and user will be redirected to the next booking page
        if( "<?php echo $dataEnteredCorrectly ?>"){
            document.location.replace("BookingCompleted.php"); // Redirect to next booking page
        } 
    </script>

    <!-- Below is the form where user selects if they want delivery (incl or excl. setup) or collection -->  
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
        <table>
           <tr>
                <td> Delivery or Click-and-Collect  </td>
                <td class="dropdown" >
                    <select name="delivOrColl">
                        <!-- number_format((float)$totalSetupCost, 2, '.', ''); ## This will ensure 7 becomes 7.00 and 8.123 => 8.12 and 7.589 => 7.59. Note it is now a string    src: https://stackoverflow.com/questions/4483540/show-a-number-to-two-decimal-places -->
                        <option value="DeliverySetup" > Delivery (incl. Setup) & Collection (Additional Cost: €<?php echo number_format( (float)($deliveryCost + $totalSetupCost), 2, '.', '' ); ?> ) </option>
                        <option value="Delivery" > Delivery (excl. Setup) & Collection (Additional Cost: €<?php echo number_format( (float)($deliveryCost), 2, '.', '' ); ?> ) </option>
                        <option value="ClickCollect" > Click-and-Collect & Return (Free) </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td> Special Instructions </td>    
                <td> <input type="text" name="specInst" style="height:60%; width:90%;" ></td>      
            </tr>
            <tr>
                <td>
                    <!-- Added a lot of styling so was easily visible to users -->
                    <input type="submit" name = "Submit" value = "Confirm Order" style="height:20%; width:40%; background-color:aqua; color:black; font-size: 20px; font-weight: bold;">
                </td>
            </tr>
        </table>   
    </form> 

    
    
</body>

        
</html>