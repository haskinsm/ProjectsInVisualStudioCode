<!-- 
    Purpose of Script: Dropdown List of Products
    Written by: Jason Yang
    last updated: Jason 16/02/21
-->
<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!--This is the Head section-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Choose Product Page</title>
    </head>
    <body>
        <img src="logo.png" alt="logo" height="100" width="220">
        <!--This gives the user the option to view all events in a new tab i.e. access a report page-->
        <h4>You can view a full list of our products <a href="all_products.php" target="_blank">here</a></h4>
        <h4>Select product: </h4>
        <!--Goes to select_product_success.php page if details correctly entered-->
        <form action="select_product_success.php" method="post" name="signupform" id="signupform">            
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
                    echo "<option value = '{$row['Product ID']}'>{$row['Product Name']}</option>";
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
        <h5>Change your mind?</h5>
        <button><a href="member_page.php"> Back to Members' Area </a></button>
    </body> 
</html>
