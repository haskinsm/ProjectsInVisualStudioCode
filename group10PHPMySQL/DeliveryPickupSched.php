<!-- 
    Purpose of Script: Delivery Pick up schedule enter date and then display everything for that day
    Written by: Michael H
    last updated: Michael 15/02/21
-->

<?php
    // Start the session
    session_start();

    $_SESSION = array(); ## To unset all at once
    session_destroy();
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

        // Will enter here once submit has been hit, date will be stored as a session variable
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
        }
    ?>

    <!--
    <script language="javascript">	
        // Will enter below condition if date has been submitted and user will be redirected to the report page
        if( "************************php echo $dateEntered ?>"){
             document.location.replace("FormCompletion.php");
        }
    </script>
    -->

    <h2>Choose date for delivery/collection schedule: </h2>
       
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

    <h2> Results for Date <?php echo $date ?>: </h2>

    <table>
        <tr> 
            <th>Customer Name </th>
            <th>Customer Business Address</th>
            <th>Customer Eircode</th>
            <th>Customer Phone number</th>
            <th>Booking ID </th>
            <th>Delivery/Collection</th>
            <th>Afternoon/Morning</th>
            <th>Items</th>
            <th>Qty</th>
        </tr>
        <?php
            if( $dateEntered){
                //Connect to SQL database
                $link = mysqli_connect("localhost","group_10","Ugh3Aiko","stu33001_2021_group_10_db");
            
                //Access the SQL database
                // This will only get results for deliveries
                $sql = "SELECT Business_Name, Business_Address, Eircode, Business_Phone, Bookings.Booking_ID, Event_Start_Time, Product_Name, Product_QTY FROM Customers, Bookings, Order_Items, Products Where Event_Start_Date ='$date'&& Customers.Business_ID = Bookings.Business_ID && Bookings.Booking_ID = Order_Items.Booking_ID && Order_Items.Product_ID = Products.Product_ID;";
                $result = mysqli_query($link,$sql); 
                // SELECT Business_Name, Business_Address, Eircode, Business_Phone, Bookings.Booking_ID, Event_Start_Time, Product_Name, Product_QTY FROM Customers, Bookings, Order_Items, Products Where Event_Start_Date = '2021-02-24' && Customers.Business_ID = Bookings.Business_ID && Bookings.Booking_ID = Order_Items.Booking_ID && Order_Items.Product_ID = Products.Product_ID
                
                //Code adapted from Aideen's photo
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
                        echo '<td> Delivery </td>';
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


                  // This will only get results for collections
                  $sqlQ2 = "SELECT Business_Name, Business_Address, Eircode, Business_Phone, Bookings.Booking_ID, Event_Start_Time, Product_Name, Product_QTY FROM Customers, Bookings, Order_Items, Products Where Event_End_Date = '$date' && Customers.Business_ID = Bookings.Business_ID && Bookings.Booking_ID = Order_Items.Booking_ID && Order_Items.Product_ID = Products.Product_ID;";
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

            }


        ?>
    </table>


</body>
</html>