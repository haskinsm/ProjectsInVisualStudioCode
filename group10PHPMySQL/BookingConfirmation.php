<!-- 
    Purpose of Script: Users should only be broought here once they complete the MainBookingForm page. The avaialable qty with respect to their dates will be shown here
    Written by: Michael H
    last updated: Michael 03/03/21
                    Written as much as I can until Harry does the Guest Logins; Ordered items are displayed, vat is shown and have a drop down bar that works for deliv and set up costs.
                Michael 06/03/21
                    Hardcoded the SQL insertion statements for customer with ID = 9. They appear to work now. Will change once Guest Login stuff is completed. Fixed bug where String with spaces in special instructions field caused a query failure.
                Harry 07/03/21
                    Added the guest login stuff
                Michael 08/03/21 
                    Added session variables which will be used in creating the downloadable invoice on the next page
                Michael 08/03/21
                    Fixed big bug in code. Number formatting with commas was causing orders over 1000 to fail.
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

    <script language="javascript">	
        // Will enter below condition if user has returned to this page from the completed bookings page
        if( "<?php echo $_SESSION["BookingComplete"] ?>"){
            document.location.replace("HomePage.php"); // Redirect to home page. Did this so user does not accidently make multiple orders 
        } 
    </script>

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
        $_SESSION["VATRate"] = $VATRate; ## This will be used in generating the downloadable invoice
        

        ## Take in km distance outside dublin from session variable and then calculate Delivery Cost
        $kmDistFromDublin = $_SESSION["kmOutsideDublin"];
        $deliveryCost = $dublinDelivRate + $kmDistFromDublin*$delivChargePerKm;  ## If Dublin: deliveryCost = 75 + 0*(0.50) = 75. Note these numbers can for deliv charges can be changed in the DB
        $_SESSION["delivCost"] = $deliveryCost; ## This will be used in generating the downloadable invoice

        $dataEnteredCorrectly = FALSE;
        // Will enter here once submit has been hit
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
               // Check if customer logged in
	   
            if (! isset($_SESSION['Email'])){
                $email_err="";
                $email=$_POST['Email'];
                $sql = "SELECT Business_Email FROM Customers WHERE Business_Email = '$email'";

                    $mailresult = mysqli_query($link,$sql); 
                    if(mysqli_num_rows($mailresult)>0){

                            $email_err = "This email is already taken.";
                        } 
                    else {
                        $name  = $_POST['name']; 
                        $address  = $_POST['address'];
                        $course   = $_POST['course'];
                        $phone    = $_POST['phone'];
                        $query =   "INSERT INTO Customers set 
                                    Business_Name    	= '".str_replace("'", "\'", $name)."', 
                                    Eircode    		= '$course',
                                    Business_Email     	= '$email',
                                    Business_Phone 		=  $phone,
                                    Business_Address   	= '$address' ";
                        
                        $Result = mysqli_query($link , $query);

                    }
                            
            }

            if ((isset($_SESSION['Email']) or isset($_POST['Email'])) and $email_err==""){
		
		
              	$business_email=$_SESSION['Email']?$_SESSION['Email']:$_POST['Email'];
		

                $specialInstr = ""; 
                $delivSetupPickUp = "";

                $delivSetupPickUp = $_POST["delivOrColl"];
                $specialInstr = $_POST["specInst"];
                $specialInstr = mysqli_real_escape_string($link, $specialInstr); ## Need this or insert query completely fails if user enters in a string with spaces in special instructions field. This was just a tad infuriating to figure out

                
                ## Prepare variables for insertion into bookings table
                $deliveryStatus = "N/a";
                $collStatus = "Not Collected"; ##Collection field doubles up as Collection of delivered items and collection of pick ups
                $returnStatus = "N/a";
                $setup = "N/a";
                $delivOrPickUp = "Delivery";

                if( $delivSetupPickUp == "ClickCollect"){
                    $returnStatus = "Not Returned";
                    $delivOrPickUp = "Collection"; ## User is collecting order
                    $cost = $_SESSION["totalBeforeDeliv"]; ## Needed to use session variables as couldnt access $totalDueBeforeDeliv up here
                } elseif( $delivSetupPickUp == "DeliverySetup" ){
                    $setup = "Not set-up";
                    $deliveryStatus = "Not Delivered";
                    $cost = $deliveryCost + $_SESSION["totalSetupCost"] +  $_SESSION["totalBeforeDeliv"];
                } else{ ## Delivery Only
                    $deliveryStatus = "Not Delivered";
                    $cost = $deliveryCost +  $_SESSION["totalBeforeDeliv"];
                }

                $startTime =  $_SESSION["startTime"];
                $endTime =  $_SESSION["endTime"];

                $_SESSION["delivMethod"] = $delivSetupPickUp; ## This will be used in generating the downloadable invoice

                // Connect to SQL database
                include ("ServerDetail.php");

                ## Now Access the SQL database 
                ## This query will insert all relevant info into the bookings table
                $sql = "INSERT INTO Bookings (Total_Price_Incl_VAT_Del_Setup, Business_Email, Event_Start_Date, Event_End_Date, Event_Start_Time, Event_End_Time, Set_Up, Delivery_Status, Collection_Status, Return_Status, Delivery_Or_Collection, Special_Instructions)";
                $sql.= "VALUES ('$cost', '$business_email', '$startDate', '$endDate', '$startTime', '$endTime', '$setup', '$deliveryStatus', '$collStatus', '$returnStatus', '$delivOrPickUp', '$specialInstr')";
                $result = mysqli_query($link,$sql); 

		
                $prodCount = 0; ## Will be set to qty of products displayed. Need this to help with naming the relevant session variables
                $prodCount = $_SESSION["prodCount"];

                ## 1st need to get the auto generated Booking ID for this booking
                $sqlQ2 = "SELECT Booking_ID FROM Bookings WHERE Business_Email = '$business_email' ORDER BY Date_Of_Order DESC "; ## Only need to select Booking_IDs for the customer nd then order them by order date which will give the correct booking ID first
                
                $resultQ2 = mysqli_query($link,$sqlQ2); 

                $rowQ2 = mysqli_fetch_assoc($resultQ2);
                $thisBookingID = $rowQ2["Booking_ID"]; 

                for($x = 1; $x <= $_SESSION["prodCount"]; $x++){

                    $thisProdQty = $_SESSION["product".$x."qty"];
                    $thisProdID =  $_SESSION["prod".$x."ID"];

                    ## Now insert into table the item that was ordered, the qty and the booking ID
                    ## Should only do this if the qty the cutomer has ordered is greater than 0
                    if( $thisProdQty > 0){
                        $sqlQ3 = "INSERT INTO Order_Items (Booking_ID, Product_ID, Product_Qty) VALUES ('$thisBookingID','$thisProdID','$thisProdQty' )";
                        $resultQ3 = mysqli_query($link,$sqlQ3); 
                    }
                }

                ## Create a session variable storing this Booking ID. This will be used in displaying the invoice
                $_SESSION["Booking_ID"] = $thisBookingID;
                $_SESSION['history']=""; ## To do with Guest logins

                $dataEnteredCorrectly = TRUE;  ## When this is true some code in a JavaScript block will redirect user to Booking confirmed page
            }

        }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 

        <!-- Start of if statement -->
        <?php if (!$_SESSION['Email']) {

            $_SESSION['history']='Booking'; ?>
            <h2> Enter your details to continue as guest or <a href="CustomerLogin.php" style="color: white;">Login</a>/<a href="CustomerSignUp.php" style="color: white;">Sign Up</a></h2>
                <div class="row"><?php echo $email_err; ?></div>
                <div class="row">
                    <div class="col-lg-2 col-md-2">
                        Email:
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <input type="text" name="Email" class="form-control" value="<?php echo isset($_SESSION['Email'])?$_SESSION['Email']:""; ?>" required>
                    </div>
  	           </div>  
   		        <div class="row">
                        <div class="col-lg-2 col-md-2">
                            Business/Customer Name:
                        </div>
                        <div class="col-lg-8 col-md-8">
                            <input type="text" class="form-control" id="name" name="name"  required="required">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-2">
                            Business/Customer Address:
                        </div>
                        <div class="col-lg-8 col-md-8">
                            <textarea class="form-control" rows="3" id="address" name="address"  
                                      required="required"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-2">
                            Eircode:
                        </div>
                        <div class="col-lg-8 col-md-8">
                            <input type="text" class="form-control" id="course" name="course" required="required">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-2">
                            Phone Number: 00 353
                        </div>
                        <div class="col-lg-8 col-md-8">
                            <input type="number" minlength="10" maxlength="10" class="form-control" id="phone" name="phone" required="required">
                        </div>
                    </div>  
                </div> 
        <?php } ?>
        <!-- End of if statement -->

        <br>

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
                            echo '<td style="border: 1px solid black;">€'.number_format( (float)($rowTotal), 2, '.', ',' ).'</td>';
                        
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

                ## Now need to create a session variable for totalDueBeforeDelivery and totalSetupCost. Need to do this as my insertion statement for the booking is in the first php code black above.
                $_SESSION["totalBeforeDeliv"] = $totalDueBeforeDeliv;
                $_SESSION["totalSetupCost"] = $totalSetupCost;
            
            ?>
        </table>
        <table>
            <tr>
                <td> </td>
                <td></td>
                <td> </td>
                <td> Subtotal (Excl. Deliv & Setup) </td>
                <td style="border: 1px solid black;"> €<?php echo number_format( (float)($subtotal), 2, '.', ',' ); ?> </td> <!-- Number comma needs to be here as was causing failures -->
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td> VAT at <?php echo ($VATRate*100) ?>% </td> <!-- Number comma needs to be here as was causing failures -->
                <td style="border: 1px solid black;"> €<?php echo number_format( (float)($VAT), 2, '.', ',' ); ?>  </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td> Total Due (Excl. Deliv & Setup) </td>
                <td style="border: 1px solid black;"> €<?php echo number_format( (float)($totalDueBeforeDeliv), 2, '.', ',' ); ?> </td>  
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
        <!--  <form method="post" action="< ?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">   --> 
        <table>
           <tr>
                <td> Delivery or Click-and-Collect  </td>
                <td class="dropdown" >
                    <select name="delivOrColl">
                        <!-- number_format((float)$totalSetupCost, 2, '.', ''); ## This will ensure 7 becomes 7.00 and 8.123 => 8.12 and 7.589 => 7.59. Note it is now a string    src: https://stackoverflow.com/questions/4483540/show-a-number-to-two-decimal-places -->
                        <option value="DeliverySetup" > Delivery (incl. Setup) & Collection (Total Booking Cost: €<?php echo number_format( (float)($deliveryCost + $totalSetupCost + $totalDueBeforeDeliv), 2, '.', ',' ); ?> )</option>
                        <option value="Delivery" > Delivery (excl. Setup) & Collection (Total Booking Cost: €<?php echo number_format( (float)($deliveryCost + $totalDueBeforeDeliv), 2, '.', ',' ); ?> ) </option>
                        <option value="ClickCollect" > Click-and-Collect & Return (Total Booking Cost: €<?php echo number_format( (float)($totalDueBeforeDeliv), 2, '.', ',' ); ?> ) </option>
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