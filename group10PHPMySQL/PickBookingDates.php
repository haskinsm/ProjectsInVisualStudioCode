<!-- 
    Purpose of Script: Pick booking start and end dates
    Written by: Michael H
    last updated: Michael 01/03/21
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
    <title> Pick Booking start & end date </title>
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

    <?php
        // define variables and set to empty values
        $startDate = $endDate = "";
        $startDateErr = $endDateErr = "";
        $datesEntered = FALSE;

        // Will enter here once submit has been hit
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        	
             ## echo(date('Y-m-d', strtotime('1999-12-31'))."<br>");  outputs:1999-12-31
            if (empty($_POST["startDate"])) {
                $startDateErr = "Must enter an end date";
            } else {
                $startDate = date('Y-m-d', strtotime($_POST["startDate"])); ##https://stackoverflow.com/questions/30243775/get-date-from-input-form-within-php
            }

            ## echo(date('Y-m-d', strtotime('1999-12-31'))."<br>");  outputs:1999-12-31
            if (empty($_POST["endDate"])) {
                $endDateErr = "Must enter a start date";
            } else {
                $endDate = date('Y-m-d', strtotime($_POST["endDate"])); ##https://stackoverflow.com/questions/30243775/get-date-from-input-form-within-php
            }

            ## Now check if end date is before or on the same day as the event
            if ( $endDate <= $startDate){
                $endDateErr = "Error: End date must be at least 1 day after start date of event. Please try again.";
                ## Now print this error out as a header 
                echo '<h2 style = "color:red; text-shadow: -1px -1px 0 #000;">'.$endDateErr.'</h2>'; 
                ## The styling here gives the font a red colour and outlines it will black to increase readability 
            }

            $todaysDate = date('Y-m-d');  ## date('Y-m-d') should get todays date. If today was 1st March 2021 it will be formatted 2021-03-01 
            ## Code below will set the earliest dates that bookings will be taken for. At the moment it is set so that company receives min of 2 days notice
            $earliestStartDate = date('Y-m-d', strtotime($todaysDate. ' + 2 days') );
           
            if(  $earliestStartDate >  $startDate ){
                $startDateErr = "Error: Start date must be at least one full working day from todays date.";
                ## Now print this error out as a header 
                echo '<h2 style = "color:red; text-shadow: -1px -1px 0 #000;">'.$startDateErr.' Earliest possible order start date is '.date('d-m-Y', strtotime($earliestStartDate) ).'</h2>'; 
                ## The styling here gives the font a red colour and outlines it will black to increase readability
            }

            ## Will only enter if no discovered errors
            if( $endDateErr == "" && $startDateErr == "" ){
                $datesEntered = True;

                ## Now store dates as session varaibels, so they are accessible in next booking page
                $_SESSION["startDate"] = $startDate;
                $_SESSION["endDate"] = $endDate;
            } 
        }
    ?>

    
    <script language="javascript">	
        // Will enter below condition if dates have been submitted and user will be redirected to the next booking page
        if( "<?php echo $datesEntered ?>"){
            document.location.replace("MainBookingPage.php"); // Redirect to next booking page
        } 
    </script>
    

    <h2>
        Please select Event start and end dates below. 
        <br> 
    </h2>

    <h5>
        Note: The event start date is the dates your order will be delivered or picked-up, and the event end date is the date your order will be collected or returned. 
        <br>
        You can decide if you want deivery or click-and-collect on a subsequent page. 
        If you select delivery there is an option to have your ordered items set up for you.
    </h5>

     <!-- Below is the fom where the user enters dates -->  
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
        <table>
            <tr>
                <td>Event start date:</td>
                <td> <input type="date" name="startDate" required> </td>           
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

    <br>



</body>
</html>