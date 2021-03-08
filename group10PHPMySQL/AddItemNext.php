<!-- 
    Purpose of Script: Add new Items confirmation page
    Written by: Jason Yang
    last updated: Jason 1/3/21, 5/3/21
    Add discount check
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Add Items </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>

        <?php include 'UniversalMenuBar.php';
        echo '<br>';
        include 'ManagerMenuBar.php';

        include ("ServerDetail.php"); 
        
        //Set variables from previous page
        $product_name = $_POST["product_name"];
        $rental_fee = $_POST["rental_fee"];
        $setup_cost = $_POST["setup_cost"];
        $discount_rate = $_POST["discount_rate"];
        $quantity_of_item = $_POST["quantity"];

        //Allow special character input
        //Code adapted from https://stackoverflow.com/questions/2584066/how-to-insert-special-characters-into-a-database
        $product_name=mysqli_real_escape_string($link,$product_name);
       
        
        //Checks to see if product name is entered
        if(!$product_name)
        {
            echo 'Please enter the name of your item.';
            exit;
        }

        //Checks to see if rental fee is entered
        if(!$rental_fee)
        {
            echo 'Please enter the rental fee per 48 hours.';
            exit;
        } 

        //Check if discount is more than actual cost
        if($discount_rate > $rental_fee)
        {
            echo "Discount cannot be more than rental price!";
            exit;
        }

        //Checks to see if setup cost is entered
        if(!$setup_cost)
        {
            echo 'Please enter the setup cost.';
            exit;
        }

        //Checks to see if quantity is entered
        if(!$quantity_of_item)
        {
            echo 'Please enter the quantity of your item.';
            exit;
        }

        //Insert all values into SQL database
        $q  = "INSERT INTO Products ("; 
        $q .= "Product_Name, Rental_Fee, Setup_Cost, Quantity";
        $q .= ") VALUES (";
        $q .= "'$product_name', '$rental_fee', '$setup_cost', '$quantity_of_item')"; 

        $result = $link->query($q);   
        
        //Go to AddItemSuccess page
        //header('Location: AddItemSuccess.php');
        ?>
        <h2>Item added successfully!</h2>
    </body> 
</html>
