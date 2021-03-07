<!-- 
    Purpose of Script: Rental Freq report. Can specify a range of dates. Default is all time.
    Written by: Michael Haskins
    last updated: Michael 17/02/21
                     Written & working.
                  Michael 07/03/21
                    Making minor change to table styling and made the report date felxible.
-->

<?php
    // Start the session
    session_start();

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
    <title> Rental Freq </title>
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
        td{
                text-align: center; 
                vertical-align: middle;
        }
    </style>
</head>
<body>

    <?php include 'UniversalMenuBar.php';?> <!-- Imports code for menu bar from another php file-->

    <br>

    <?php include 'ManagerMenuBar.php';?> <!-- Imports code for manager menu bar from another php file-->

    <?php
        $endDate = "";
        $startDate = "";
    
        // Will enter here once submit has been hit
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        	
            ## echo(date('Y-m-d', strtotime('1999-12-31'))."<br>");  outputs:1999-12-31
            $startDate = date('Y-m-d', strtotime($_POST["startDate"])); ##https://stackoverflow.com/questions/30243775/get-date-from-input-form-within-php
            $endDate = date('Y-m-d', strtotime($_POST["endDate"]));

            $dateEntered = True;
        } else { 
            ## Set default dates to a min and max
            $startDate = '2021-01-01'; ## There will be no orders before this date
            $endDate = '2060-01-01'; ## This will be used as default max date
        }
    ?>

    <br>

    <h2> Please Select Dates for the below report </h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
        <table>
            <tr>
                <td> Start Date:</td>
                <td> <input type="date" name="startDate" required> </td>           
            </tr>
            <tr>
                <td> End Date:</td>
                <td> <input type="date" name="endDate" required> </td>           
            </tr>
            <tr>
                <td>
                    <input type="submit" name = "Submit" value = "Submit">
                </td>
            </tr>
        </table>   
    </form> 


    <h2> 
        <?php

            if($startDate == '2021-01-01' ){ ## Then working with default dates -> All time
                echo 'All time Rental Frequency: ';
            } else{
                echo 'Rental Frequency between '.date("d M Y", strtotime($startDate)).' and '.date("d M Y", strtotime($endDate)).':';
            }
        ?>
    </h2>

    <table>
        <tr> 

            <th> Product ID </th>
            <th> Product Name </th>
            <th> Stock </th>
            <th> Number of Bookings product has been ordered in </th>
            <th> Qty Ordered </th>
        
        </tr>
        <?php
                // Connect to SQL database
                include ("ServerDetail.php");
            
                //Access the SQL database
                // This will get product ID, name, qty (stock) 
                $sql = "SELECT Product_ID, Product_Name, Quantity FROM Products ";
                $result = mysqli_query($link,$sql); 
              
                //Code adapted from Aideen's photo
                while($row=mysqli_fetch_assoc($result)){
                    echo '<tr>';

                    $productID = $row["Product_ID"];
                    echo '<td>'.$productID.'</td>';
                    echo '<td>'.$row["Product_Name"].'</td>';
                    echo '<td>'.$row["Quantity"].'</td>';

                     //Access the SQL database
                    // This will get the number of times and the QTY of the relevant product that has been ordered. Between the selected dates. If no dates are selcted the default is all time.
                    $sql2Q = "SELECT count(Product_ID), sum(Product_Qty) FROM Order_Items, Bookings WHERE Product_ID = '$productID'  && Event_End_Date <= '$endDate' && Event_Start_Date >= '$startDate' && Bookings.Booking_ID = Order_Items.Booking_ID ";
                    $result2Q = mysqli_query($link,$sql2Q); 

                    while($row2Q=mysqli_fetch_assoc($result2Q)){
                        $numBookingsOrderedIn = $row2Q["count(Product_ID)"];
                        echo '<td>'.$numBookingsOrderedIn.'</td>';

                        // If ordered in No bookings show Qty ordered to be 0 
                        if( $numBookingsOrderedIn == 0){
                            echo  '<td>0</td>';
                        } else {
                            echo '<td> <b>'.$row2Q["sum(Product_Qty)"].'</b> </td>'; ## Adding bold tags to increase readabilityy
                        }
                    }

                    echo '</tr>';
                }


        ?>
    </table>


</body>
</html>