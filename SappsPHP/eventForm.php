<?php
// Start the session
session_start();

$_SESSION = array(); ## To unset all at once
session_destroy();

?>
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
        h4{
            background-color: aquamarine;
            width: fit-content;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DUMMS Event form</title>
</head>
<body>
    <img src="dumms.png" alt="DUMMS" height="136" width = "193"> <h2>DUMMS Event Form</h2>
    
    <?php
        // define variables and set to empty values
        $eventid = $organstudnum = $title = $price = $location = $date = $capacity = $starttime = $durationmins = "";
        $eventidErr = $organstudnumErr = $titleErr = $priceErr = $locationErr = $dateErr = $capacityErr =$starttimeErr = $durationminsErr = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        	if (empty($_POST["title"])) {   ## If empty => error
                $titleErr = "Title of event is required";
            } else {
                $title = test_input($_POST["title"]); ## Uses a test input function written below
            }
            
            if (empty($_POST["eventid"])) {
                $eventidErr = "Event ID is required";
            } else {
                $eventid = test_input($_POST["eventid"]);
                if ( strlen($eventid) != 4) {  ## I want event id to be 4 chars long only
                    $eventidErr = "EventId must be 4 chars long";
                }
            }
            
            if (empty($_POST["organstudnum"])) {
                $organstudnumErr = "Organizer Student Number is required";
            } else {
                $organstudnum = test_input($_POST["organstudnum"]);
                if ( !is_numeric($organstudnum) ){
                    $organstudnumErr = "Invalid Organizer Student Number. Must contain numbers only.";
                }
                if( count_digit($organstudnum) > 10 || count_digit($organstudnum) < 5){   ## Did this as think most valid student numbers are between 5 and 10 digits long
                    $organstudnumErr = "Invalid Student Number. Must be between 5 and 10 digits in length.";
                } else { ## Checks if user has entered in a registered member.
                    
                    include ("detail.php"); 

                    $queryReg = "SELECT * FROM member WHERE dbstud_num = '$organstudnum'";
                    $resultQ = $db->query($queryReg);

                    if ($resultQ->num_rows <= 0){  ## If no row is found then will have zero rows so will enter here and error message will be recorded
                        $organstudnumErr = "There is no registered DUMSS member with this ID.";
                    }
                }

            }
        
            if (empty($_POST["price"])) {
                $priceErr = "Must enter the price of a ticket for this event";
            } else {
                $price = test_input($_POST["price"]);
                if ( !is_numeric($price) ){
                    $priceErr = "Invalid Price. Must contain numbers only.";
                }
            }

            if (empty($_POST["capacity"])) {
                $capacityErr = "Must enter the capacity for this event";
            } else {
                $capacity = test_input($_POST["capacity"]);
                if ( !is_numeric($capacity) ){
                    $capacityErr = "Invalid capacity. Must contain numbers only.";
                }
            }

            if (empty($_POST["duration"])) {
                $durationErr = "Must enter the duration of this event in minutes";
            } else {
                $duration = test_input($_POST["duration"]);
                if ( !is_numeric($duration) ){
                    $durationErr = "Invalid duration. Must contain numbers only. e.g for 5 mins enter 5.";
                }
            }

            if (empty($_POST["location"])) {
                $locationErr = "Must enter the location for this event";
            } else {
                $location = test_input($_POST["location"]);
            }
        
            ## echo(date('Y-m-d', strtotime('1999-12-31'))."<br>");  outputs:1999-12-31
            if (empty($_POST["date"])) {
                $dateErr = "Must enter one date";
            } else {
                $date = date('Y-m-d', strtotime($_POST["date"])); ##https://stackoverflow.com/questions/30243775/get-date-from-input-form-within-php
            }
            
            if (empty($_POST["starttime"])) {
                $starttimeErr = "Must enter the start time of the event";
            } else {
                
               $starttime = $_POST["starttime"];
            }

            ## Will only enter if no discovered errors
            if( $eventidErr == "" && $organstudnumErr == "" && $titleErr == "" && $priceErr == "" && $locationErr == "" && $dateErr == "" && $capacityErr == "" && $starttimeErr == "" && $durationminsErr == "" ){
                include ("detail.php"); 

                $dataSubmitted = True;

                $q  = "INSERT INTO event (";
                $q .= "dbevent_id, dborganizer_id, dbprice, dbtitle, dbdate, dbcapacity, dblocation, dbstart_time, dbduration_mins";
                $q .= ") VALUES (";
                $q .= "'$eventid', '$organstudnum', '$price', '$title', '$date', '$capacity', '$location', '$starttime', '$duration')";

                $result = $db->query($q);

            }
        }

        function test_input($data) {
            $data = trim($data); ##Strip unnecessary characters (extra space, tab, newline) from the user input data (with the PHP trim() function)
            $data = stripslashes($data); ##Remove backslashes (\) from the user input data (with the PHP stripslashes() function)
            $data = htmlspecialchars($data); ##For security reasons I think
                ## When we use the htmlspecialchars() function; then if a user tries to submit the following in a text field:
                ## <script>location.href('http://www.hacked.com')</script>
                
                ##- this would not be executed, because it would be saved as HTML escaped code, like this:
                
                ## &lt;script&gt;location.href('http://www.hacked.com')&lt;/script&gt;
                
                 ##The code is now safe to be displayed on a page or inside an e-mail.
            return $data;
        }

        ## Used to count digits of entered stud num
        function count_digit($number)
        {
            return strlen((string) $number);
        }

    ?>

    <script language="javascript">	
        // Will enter below condition if data has been submitted to database and user will be redirected to form completion page
        if( "<?php echo $dataSubmitted ?>"){
             document.location.replace("FormCompletion.php");
        }

    </script>

    <h4>Event Details (*Required Fields)</h4>
  
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
        <table>
            <tr>
                <td>Event ID (Must be 4 chars only)*:</td>
                <td><input type="text" name ="eventid" id = "eventid" size = 10 value="<?php echo $eventid;?>"></td>
                <span class="error"> <?php if(!empty($eventidErr)){ echo "Error: *".$eventidErr."<br>";}?></span>
            </tr>
            <tr>
                <td>Organizer Student Number*:</td>
                <td><input type="text" name ="organstudnum" id = "organstudnum" size = 10 value="<?php echo $organstudnum;?>"></td>
                <span class="error"> <?php if(!empty($organstudnumErr)){ echo "Error: *".$organstudnumErr."<br>";}?></span>
            </tr>
            <tr>
                <td>Ticket Price*:</td>
                <td><input type="text" name ="price" id = "price" size = 10 value="<?php echo $price;?>"></td>
                <span class="error"> <?php if(!empty($priceErr)){ echo "Error: *".$priceErr."<br>";}?></span>
            </tr>
            <tr>
                <td>Location*:</td>
                <td><input type="text" name ="location" id = "location" size = 40 value="<?php echo $location;?>"></td>
                <span class="error"> <?php if(!empty($locationErr)){ echo "Error: *".$locationErr."<br>";}?></span>
            </tr>
            <tr>
                <td>Event Title*:</td>
                <td><input type="text" name ="title" id = "title" size = 40 value="<?php echo $title;?>"></td>
                <span class="error"> <?php if(!empty($titleErr)){ echo "Error: *".$titleErr."<br>";}?></span>
            </tr>
            <tr>
                <td>Date of Event*:</td>
                <td><input type="date" name ="date" id = "date" size = 10 value="<?php echo $date;?>"></td> 
                <span class="error"> <?php if(!empty($dateErr)){ echo "Error: *".$dateErr."<br>";}?></span>
            </tr>
            <tr>
                <td>Duration in minutes*:</td>
                <td><input type="text" name ="duration" id = "duration" size = 10 value="<?php echo $duration;?>"></td>
                <span class="error"> <?php if(!empty($durationErr)){ echo "Error: *".$durationErr."<br>";}?></span>
            </tr>
            <tr>
                <td>Capacity*:</td>
                <td><input type="text" name ="capacity" id = "capacity" size = 10 value="<?php echo $capacity;?>"></td>
                <span class="error"> <?php if(!empty($capacityErr)){ echo "Error: *".$capacityErr."<br>";}?></span>
            </tr>
            <tr>
                <td>Start Time of Event*:</td>
                <td><input type="time" name ="starttime" id = "starttime" size = 10 value="<?php echo $starttime;?>"></td>
                <span class="error"> <?php if(!empty($starttimeErr)){ echo "Error: *".$starttimeErr."<br>";}?></span>
            </tr>
            
            <tr>
                <td>
                    <input type="submit" name = "Submit" value = "Submit Form to database">
                </td>
            </tr>
        </table>
    </form>
    
    <br><br><br><br><br>
    <a href="DUMSSMainPage.php"> DUMSS Home Page </a>
</body>
</html>

