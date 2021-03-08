<!-- 
    Purpose of Script: Adjust Quantity of Items
    Written by: Jason Yang
    last updated: Jason 2/3/21, 3/3/21
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
    <title> Adjust Item Quantity </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>

    <?php include 'UniversalMenuBar.php';
    echo '<br>';
    include 'ManagerMenuBar.php';?> 

<h4>Select Item: </h4>
        <!--Goes to AdjustItemQuantityNext.php page if details correctly entered-->
        <form action="AdjustItemQuantityNext.php" method="post" name="signupform" id="signupform">            
            <table>
            <tr>  
            <td>
            <!--This select statement stores the users choice-->
            <select name="product_list" id="product_list">
                <?php
                //Connect to SQL database
                include ("ServerDetail.php");
        
                //Access the SQL database
                $sql = "SELECT * FROM Products;";
                $result = mysqli_query($link,$sql); 

                //Code adapted from Aideen's photo, thank you Aideen!
                while($row=mysqli_fetch_assoc($result)){
                    echo "<option value = '{$row['Product_ID']}'>{$row['Product_Name']}</option>";
                }
                ?>
                </select>
            </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                <input type="submit" value="Submit">
            </tr>
            </table>
        </form>
    </body> 
</html>