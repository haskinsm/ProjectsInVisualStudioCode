<!-- 
    Purpose of Script: Users should only be broought here once they have selceted dates in the PickBookingDetails.php page. The avaialable qty with respect to their dates will be shown here
    Written by: Michael H
    last updated: Michael 01/03/21, Michael 02/03/21
                Form partially complete; Has dropdown bars showing availability of each item, Fixed N/a and wrote code for session vars,
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

</head>
<body>

    <?php include 'UniversalMenuBar.php';?> <!-- Imports code for menu bar from another php file-->

    <br> 

    <h2> Booking Confirmation </h2>

    <?php 

        ## Store dates as session variables and output to user
        $startDate = $_SESSION["startDate"];
        $endDate = $_SESSION["endDate"];
        ## Now display start and end dates selceted to user 
        echo '<h3> Start date selected:  '.date('d-m-Y', strtotime($startDate) ).'. <br> End date selected:  '.date('d-m-Y', strtotime($endDate) ).'. </h3>'; 
        ## Code above also converts the dates back to more common d-m-Y format

        $dataEnteredCorrectly = FALSE;
      
    ?>


    <!-- Now redirect user to following page if data has been entered -->
    <script language="javascript">	
        // Will enter below condition if dates have been submitted and user will be redirected to the next booking page
        if( "<?php echo $dataEnteredCorrectly ?>"){
            document.location.replace("BookingCompleted.php"); // Redirect to next booking page
        } 
    </script>


    <!-- Below is the form where user selects if they want delivery or collection -->  
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
        <table>
           <tr>
                <td> Delivery or Click-and-Collect  </td>
                <td class="dropdown">
                    <select name="delivOrColl">
                        <option value="Delivery"> Delivery </option>
                        <option value="Afternoon"> Click-and-Collect </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Event end date:</td>
                <td> <input type="date" name="endDate" required> </td>           
            </tr>

            <tr>
                <td>
                    <input type="submit" name = "Submit" value = "Submit">
                </td>
            </tr>
        </table>   
    </form> 

    
    
</body>

        
</html>