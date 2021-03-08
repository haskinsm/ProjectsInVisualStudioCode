<!-- 
    Purpose of Script: Booking completetion page. User can see their invoice and booking ID number
    Written by: Michael H
    last updated: Michael 06/03/21
      Written
            Michael 08/03'21
                Im just adding the invoice stuff myself. Have everything working perfectly. There are currenlty problems with the Guest & Customer Login, but all of my stuff works perfectly if you hardcode the guest email.
                
        
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
     <!-- Now Overriding styling from the WebsiteStyle.CSS sheet and adding styling to tables as want tables to have same width & the text to allign centrally & borders etc  -->
     <style>
        table {
            table-layout: fixed ;
            width: 65% ; /* Width set at 50% of window */
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

    <?php
        ## Will access some of the session variables here to be used in the below table
        $startDate = $_SESSION["startDate"];
        $endDate = $_SESSION["endDate"];
        $VATRate = $_SESSION["VATRate"]; ## this is stored in previous page from a query to the database. 
        $startTime =  $_SESSION["startTime"];
        $endTime =  $_SESSION["endTime"];
        $totalSetupCost = $_SESSION["totalSetupCost"];
        $deliveryCost =  $_SESSION["delivCost"];
        $delivSetupPickUp = $_SESSION["delivMethod"]; ## Can be ClickCollect or DeliverySetup or Delivery 
        $totalDueBeforeDeliv =  $_SESSION["totalBeforeDeliv"];

        $delivOrPickUp = "Delivery";
        $returnOrCollection = "Collection";

        ## Now format DeliveryCost and setup cost nicely
        $deliveryCost = number_format( (float)($deliveryCost), 2, '.', '' ); ## Makes sure price is to two decimal places
        $totalSetupCost = number_format( (float)($totalSetupCost), 2, '.', '' ); ## Make sure price has is to two decimal places

        if( $delivSetupPickUp == "ClickCollect"){

            $delivOrPickUp = "Collection"; ## User is collecting order
            $returnOrCollection = "Return";
            $cost = $totalDueBeforeDeliv;
            $deliveryCost = "N/a";
            $totalSetupCost = "N/a";

        } elseif( $delivSetupPickUp == "DeliverySetup" ){

            $delivOrPickUp = "Delivery (Inlc. Setup)";
            $cost = $deliveryCost + $totalSetupCost +  $totalDueBeforeDeliv;

        } else{ ## Delivery Only

            $cost = $deliveryCost +  $totalDueBeforeDeliv;
            $totalSetupCost = "N/a";
        }

        ## Now format Cost nicely:
        $cost = number_format( (float)($cost), 2, '.', '' ); ## Makes sure price is to two decimal places
    ?>

    <table id="tblCustomers" cellspacing="0" cellpadding="0">
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

                        ## Calc subtotal
                        $subtotal = number_format( (float)( $subtotal + $rowTotal ), 2, '.', '' ); ## Rounds to two places so 8-> 8.00 etc
                     echo '</tr>';
                }
            }
            $VAT = number_format( (float)( $subtotal*$VATRate ), 2, '.', '' ); ## Rounds to two places so 8-> 8.00 etc
            $totalDueBeforeDeliv = number_format( (float)( $subtotal + $VAT ), 2, '.', '' ); ## Rounds to two places so 8-> 8.00 etc

        
        ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
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
            <td> <b> Total Due before any delivery & setup fees </b> </td> <!-- Added bold tags to make it easliy visible, will do this for final total too. -->
            <td style="border: 1px solid black;"> <b> €<?php echo $totalDueBeforeDeliv ?> </b>  </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td> Delivery Fees </td>
            <td style="border: 1px solid black;"> 
                <?php if($deliveryCost != "N/a"){
                    echo '€'.$deliveryCost ;
                } else {
                    echo $deliveryCost; ## Will be "N/a" in this case
                }
                ?> 
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td> Setup Fees </td>
            <td style="border: 1px solid black;"> 
                <?php if($totalSetupCost != "N/a"){
                        echo '€'.$totalSetupCost ;
                    } else {
                        echo $totalSetupCost; ## Will be "N/a" in this case
                    }
                ?>  
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td> <b> Total Due </b> </td>
            <td style="border: 1px solid black;"> <b> €<?php echo $cost ?> </b> </td>
        </tr>
        <tr>
            <td> <b> Booking ID: </b> </td>
            <td> <b> <?php echo $_SESSION["Booking_ID"]; ?> </b> </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td> <b> Booking Details: </b> </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td> <?php echo $delivOrPickUp ?>: </td>
            <td> <?php echo $startTime.' of '.$startDate ?> </td>
            <td> </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td> <?php echo $returnOrCollection ?>: </td>
            <td> <?php echo $endTime.' of '.$endDate ?> </td>
            <td>  </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td> Payment: </td>
            <td> due before <?php echo $startDate ?></td>
            <td> Contact Accounts payable by: </td>
            <td> Phone; 01 756 1113 </td>
            <td> Email;  <a href="mailto:accounts@dph.ie?body=" style="color: black;"> accounts@dph.ie </a> </td>
        </tr>
    </table>

    <br>

    <input type="button" id="btnExport" value="Download invoice as PDF" onclick="Export()" />

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script type="text/javascript">
        function Export() {
            html2canvas(document.getElementById('tblCustomers'), {
                onrendered: function (canvas) {
                    var data = canvas.toDataURL();
                    var docDefinition = {
                        content: [{
                            image: data,
                            width: 500
                        }]
                    };
                    pdfMake.createPdf(docDefinition).download("Invoice.pdf");
                }
            });
        }
    </script>


</body>
</html>