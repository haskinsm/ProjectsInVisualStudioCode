<!DOCTYPE html>
<html lang="en">
<head>
    <style>                  
        table
        th{
            text-align: left;
            background-color: cadetblue;
            color: #ffffff;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lloyd Cheesse Order</title>
</head>
<body>
    <img src="Cheese.jpg" alt="cheese" height="136" width = "193"> <h2>Lloyd Cheese Company</h2>
    <h3>Order Results</h3>
    <?php
    echo   "Order proccessed on ".date("G:i, jS \of F") . "<br>"."<br>";             #'<p> Order Processed at  </p>';
    // Create short variable names
    $cheddarqty = $_POST['cheddarqty'];
    $cashelqty = $_POST['cashelqty'];
    $gubeenqty = $_POST['gubeenqty'];
    $durrusqty = $_POST['durrusqty'];

    define("cheddarprice", 23.50);
    define("cashelprice", 45.75);
    define("gubeenprice", 36.50);
    define("durrusprice", 53.00);


    echo "Your order is as follows:"."<br>"."<br>";
    echo $cheddarqty." Montgomery cheddar"."<br>";
    echo $cashelqty." cashel blue"."<br>";
    echo $gubeenqty." gubeen"."<br>";
    echo $durrusqty." durrus"."<br>";

    //Creating a table
    // Note: Be very very careful with synatx here, its a bitch, if dealing with operators surround with brackets generally
    echo "<table>";
    echo  "<tr><th>Item</th><th>Quantity</th><th>Price</th><th>Total</th></tr>";
    echo "<tr><td>Montgomery Cheddar</td><td>$cheddarqty</td><td>".cheddarprice."</td><td>".$cheddarqty*cheddarprice."</td></tr>";
    echo "<tr><td>Cashel Blue</td><td>$cashelqty</td><td>".cashelprice."</td><td>".$cashelqty*cashelprice."</td></tr>";
    echo "<tr><td>Gubeen</td><td>$gubeenqty</td><td>".gubeenprice."</td><td>".$gubeenqty*gubeenprice."</td></tr>";
    echo "<tr><td>Durrus</td><td>$durrusqty</td><td>".durrusprice."</td><td>".$durrusqty*durrusprice."</td></tr>";
    echo "<tr><td><b>Subtotal</td><td>".($cheddarqty+$cashelqty+$gubeenqty+$durrusqty)."</td><td></td><td>".($cheddarqty*cheddarprice+$cashelqty*cashelprice+$gubeenqty*gubeenprice+$durrusqty*durrusprice)."</td></tr>"; 
    echo "<tr><td><b>Total(Incl 9% VAT)</td><td></td><td></td><td>".(($cheddarqty*cheddarprice+$cashelqty*cashelprice+$gubeenqty*gubeenprice+$durrusqty*durrusprice)*1.09)."</td></tr>"; 
    echo "</table>";
    ?>
</body>
</html>

