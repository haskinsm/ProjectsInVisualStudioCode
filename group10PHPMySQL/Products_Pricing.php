<!-- 
    Purpose of Script: Products and Pricing
    Written by: Jason Yang
    last updated: Jason 18/02/21, 19/2/21, Michael 05/05/21
    Does not account for items that are currently out of inventory. 
    Would be nice to show the available quantities of each item
      Update Notes: written, , Added discount % & setUp cost 
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Products & Pricing </title>
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

    <h2> Products & Pricing: </h2>

    <h3> All our products are rented over 48 hour periods. This means for example that if you would like to rent our products for 3 days you will be charged for two billing periods.</h3>
    <h3> Please note that these prices do not include VAT. </h3>

    <table>
        <tr> 

            <th> Product ID </th>
            <th> Product Name </th>
            <th> Normal cost / 48hr Rental </th>
            <th> Currently Discounted by </th>
            <th> Price after discount </th>
            <th> Optional Set-up Cost</th>
        
        </tr>
        <?php
                //Connect to SQL database
                include ("ServerDetail.php");
            
                //Access the SQL database
                // This will get product ID, name, qty (stock) 
                $sql = "SELECT Product_ID, Product_Name, Rental_Fee, Setup_Cost, Euro_Discount FROM Products ";
                $result = mysqli_query($link,$sql); 
              
                //Code adapted from Aideen's photo
                while($row=mysqli_fetch_assoc($result)){
                    echo '<tr>';

                    $productID = $row["Product_ID"];
                    echo '<td>'.$productID.'</td>';
                    echo '<td>'.$row["Product_Name"].'</td>';

                    $normalPrice = $row["Rental_Fee"];
                    $euroDiscount = $row["Euro_Discount"];

                    echo '<td> €'.$normalPrice.'</td>';
                    ## If there is no dicount just output '-', if there is calc the discount %. This rly improves readability
                    if( $euroDiscount == 0.00){
                        echo '<td> - </td>';
                    } else {
                        ## Below number_format function rounds to two places so 8-> 8.00, 9.768 -> 9.77 etc
                        echo '<td>'.number_format( (float)( ($euroDiscount/$normalPrice)*100), 2, '.', '' ).'% </td>'; ## Get % dicount
                    }
                    
                    echo '<td> €'.number_format( (float)( $normalPrice - $euroDiscount), 2, '.', '' ).'</td>';

                    $setUpCost = $row["Setup_Cost"];
                    if( $setUpCost == 0.00){
                        echo '<td> N/a </td>';
                    } else {
                        echo '<td>'.$row["Setup_Cost"].'</td>';
                    }

                    echo '</tr>';
                }


        ?>
    </table>


</body>
</html>