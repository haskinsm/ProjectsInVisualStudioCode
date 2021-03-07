<!-- 
    Purpose of Script: Delivery Pick up schedule enter date and then display everything for that day
    Written by: Michael H
    last updated: Michael 15/02/21, Michael 05/03/21, Michael 06/03/21
    updates log:  Written, Improved the report by outputting if the delivery includes set up also is now able to cope with pick ups and customer returns. Fully functional as of 05/03/21. Set the default date as todays date
            Michael 07/03/21
                Had to fix it since soemone changed the link between the Bookings and Customer table from the auto generated Businees_ID to Business_Email.
-->
<?php
    // Start the session
    session_start();

    ## This ensures only a logged in manager can access this report
     if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["Position"]) && $_SESSION["Position"] === Manager)){
    	header("location: ManagerLogin.php");
    	exit;
     }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Delivery/Collection Schedule </title>
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

    <?php include 'ManagerMenuBar.php';?> <!-- Imports code for manager menu bar from another php file-->


    <?php
        // define variables and set to empty values
        $date = "";
        $dateErr = "";

        // Will enter here once submit has been hit
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        	
            ## echo(date('Y-m-d', strtotime('1999-12-31'))."<br>");  outputs:1999-12-31
            if (empty($_POST["date"])) {
                $dateErr = "Must enter one date";
            } else {
                $date = date('Y-m-d', strtotime($_POST["date"])); ##https://stackoverflow.com/questions/30243775/get-date-from-input-form-within-php
            }

            ## Will only enter if no discovered errors
            if( $dateErr == "" ){
                $dateEntered = True;
            }
        } else { ## Set default date as todays date
            $todaysDate = date('Y-m-d');  ## date('Y-m-d') should get todays date. If today was 1st March 2021 it will be formatted 2021-03-01 
            $date = $todaysDate;
        }
    ?>

    <h2>Choose date for delivery/collection/pickup/return schedule: </h2>
       
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
        <table>
            <tr>
                <td>Date:</td>
                <td> <input type="date" name="date" required> </td>           
            </tr>
            <tr>
                <td>
                    <input type="submit" name = "Submit" value = "Submit">
                </td>
            </tr>
        </table>   
    </form> 

    <br>

    <h2> Results for Date <?php echo date('d-m-Y', strtotime($date) ) ?>: </h2> 
    <!-- Above date formatting converts the date back into more readable format-->

    <table>
        <tr> 
            <th>Customer Name </th>
            <th>Customer Business Address</th>
            <th>Customer Eircode</th>
            <th>Customer Phone number</th>
            <th>Booking ID </th>
            <th>Activity</th>
            <th>Afternoon/Morning</th>
            <th>Items</th>
            <th>Qty</th>
        </tr>
        <?php
            if( $dateEntered){
                 // Connect to SQL database
                include ("ServerDetail.php");
            
                //Access the SQL database
                // This will only get results for deliveries. It ensures no bookings for pick up are included. Will return each items ordered along with its booking details
                $sql = "SELECT Business_Name, Business_Address, Eircode, Business_Phone, Bookings.Booking_ID, Event_Start_Time, Set_Up, Product_Name, Product_QTY FROM Customers, Bookings, Order_Items, Products Where Event_Start_Date ='$date'&& Customers.Business_Email = Bookings.Business_Email && Bookings.Booking_ID = Order_Items.Booking_ID && Order_Items.Product_ID = Products.Product_ID && Delivery_Status != 'N/a';";
                $result = mysqli_query($link,$sql); 
                

                $previousBookingID = "";
                $currentBookingID = "";
                while($row=mysqli_fetch_assoc($result)){
                    echo '<tr>';
                    
                    $currentBookingID = $row["Booking_ID"];

                    if( $previousBookingID != $currentBookingID){
                        echo '<td>'.$row["Business_Name"].'</td>';
                        echo '<td>'.$row["Business_Address"].'</td>';
                        echo '<td>'.$row["Eircode"].'</td>';
                        echo '<td>'.$row["Business_Phone"].'</td>';
                        echo '<td>'.$currentBookingID.'</td>';

                        ## Will now output if delivery includes setup 
                        $setup = $row["Set_Up"]; ## This could either be 'N/a', 'Not set-up' or 'Set-up'
                        if( $setup == "N/a"){
                            echo '<td> Delivery </td>';
                        } else{
                            echo '<td> Delivery & Setup </td>';
                        }
                        echo '<td>'.$row["Event_Start_Time"].'</td>';
                    } else {
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                    }
                    echo '<td>'.$row["Product_Name"].'</td>';
                    echo '<td>'.$row["Product_QTY"].'</td>';

                    echo '</tr>';
                    $previousBookingID = $currentBookingID;
                }


                  // This will only get results for collections and ensures no orders which are being picked up are included. Orders which are being picked up will have 'N/a' in Delivery_Status field in bookings table
                  $sqlQ2 = "SELECT Business_Name, Business_Address, Eircode, Business_Phone, Bookings.Booking_ID, Event_Start_Time, Product_Name, Product_QTY FROM Customers, Bookings, Order_Items, Products Where Event_End_Date = '$date' && Customers.Business_Email = Bookings.Business_Email && Bookings.Booking_ID = Order_Items.Booking_ID && Order_Items.Product_ID = Products.Product_ID && Delivery_Status != 'N/a';";
                  $result2 = mysqli_query($link,$sqlQ2); 
                
                  //Code adapted from Aideen's photo
                  $previousBookingID = "";
                  $currentBookingID = "";
                  while($row=mysqli_fetch_assoc($result2)){
                      echo '<tr>';
                      
                      $currentBookingID = $row["Booking_ID"];
  
                      // This if block ensures customer details are not repeated multiple times for each booking
                      if( $previousBookingID != $currentBookingID){
                          echo '<td>'.$row["Business_Name"].'</td>';
                          echo '<td>'.$row["Business_Address"].'</td>';
                          echo '<td>'.$row["Eircode"].'</td>';
                          echo '<td>'.$row["Business_Phone"].'</td>';
                          echo '<td>'.$currentBookingID.'</td>';
                          echo '<td> Collection </td>';
                          echo '<td>'.$row["Event_Start_Time"].'</td>';
                      } else {   
                          echo '<td></td>';
                          echo '<td></td>';
                          echo '<td></td>';
                          echo '<td></td>';
                          echo '<td></td>';
                          echo '<td></td>';
                          echo '<td></td>';
                      }
                      echo '<td>'.$row["Product_Name"].'</td>';
                      echo '<td>'.$row["Product_QTY"].'</td>';
  
                      echo '</tr>';
                      $previousBookingID = $currentBookingID;
                  }



                        //Access the SQL database
                    // This will only get results for customer pickups. It ensures no bookings for pick up are included. Will return each items ordered along with its booking details
                    $sql = "SELECT Business_Name, Business_Address, Eircode, Business_Phone, Bookings.Booking_ID, Event_Start_Time, Product_Name, Product_QTY FROM Customers, Bookings, Order_Items, Products Where Event_Start_Date ='$date'&& Customers.Business_Email = Bookings.Business_Email && Bookings.Booking_ID = Order_Items.Booking_ID && Order_Items.Product_ID = Products.Product_ID && Delivery_Status = 'N/a';";
                    $result = mysqli_query($link,$sql); 
                    

                    $previousBookingID = "";
                    $currentBookingID = "";
                    while($row=mysqli_fetch_assoc($result)){
                        echo '<tr>';
                        
                        $currentBookingID = $row["Booking_ID"];

                        if( $previousBookingID != $currentBookingID){
                            echo '<td>'.$row["Business_Name"].'</td>';
                            echo '<td>'.$row["Business_Address"].'</td>';
                            echo '<td>'.$row["Eircode"].'</td>';
                            echo '<td>'.$row["Business_Phone"].'</td>';
                            echo '<td>'.$currentBookingID.'</td>';
                            echo '<td> Customer Pick-up </td>';
                            echo '<td>'.$row["Event_Start_Time"].'</td>';
                        } else {
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                        }
                        echo '<td>'.$row["Product_Name"].'</td>';
                        echo '<td>'.$row["Product_QTY"].'</td>';

                        echo '</tr>';
                        $previousBookingID = $currentBookingID;
                    }


                    

                   // This will only get results for returns. Orders which are being returned by individual customers will have 'N/a' in Delivery_Status field in bookings table
                   $sqlQ4 = "SELECT Business_Name, Business_Address, Eircode, Business_Phone, Bookings.Booking_ID, Event_Start_Time, Product_Name, Product_QTY FROM Customers, Bookings, Order_Items, Products Where Event_End_Date = '$date' && Customers.Business_Email = Bookings.Business_Email && Bookings.Booking_ID = Order_Items.Booking_ID && Order_Items.Product_ID = Products.Product_ID && Delivery_Status = 'N/a';";
                   $result4 = mysqli_query($link,$sqlQ4); 
                 
                   //Code adapted from Aideen's photo
                   $previousBookingID = "";
                   $currentBookingID = "";
                   while($row=mysqli_fetch_assoc($result4)){
                       echo '<tr>';
                       
                       $currentBookingID = $row["Booking_ID"];
   
                       // This if block ensures customer details are not repeated multiple times for each booking
                       if( $previousBookingID != $currentBookingID){
                           echo '<td>'.$row["Business_Name"].'</td>';
                           echo '<td>'.$row["Business_Address"].'</td>';
                           echo '<td>'.$row["Eircode"].'</td>';
                           echo '<td>'.$row["Business_Phone"].'</td>';
                           echo '<td>'.$currentBookingID.'</td>';
                           echo '<td> Return </td>';
                           echo '<td>'.$row["Event_Start_Time"].'</td>';
                       } else {   
                           echo '<td></td>';
                           echo '<td></td>';
                           echo '<td></td>';
                           echo '<td></td>';
                           echo '<td></td>';
                           echo '<td></td>';
                           echo '<td></td>';
                       }
                       echo '<td>'.$row["Product_Name"].'</td>';
                       echo '<td>'.$row["Product_QTY"].'</td>';
   
                       echo '</tr>';
                       $previousBookingID = $currentBookingID;
                   }
 

            }


        ?>
    </table>


</body>
</html>