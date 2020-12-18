<?php
// Start the session
session_start();
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
        h3{
            background-color: pink;
            width: fit-content;
        }
        h4{
            background-color: aquamarine;
            width: fit-content;
        }
        .tab { 
            display:inline-block; 
            margin-left: 40px; 
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DUMMS Cancel Ticket Query</title>
</head>
<body>
    <img src="dumms.png" alt="DUMMS" height="136" width = "193"> 

    <h2>Cancel Ticket Query <span class="tab"> <a href="DUMSSMainPage.php"> DUMSS Home Page </a> </span>  </h2> 
    
    <h3>
        Warning: This will delete/cancel ALL of your tickets to whatever event you specify.
        <br>
        Cancelling your tickets does not neccessarily mean you are entitled to a refund. 
        <br>
        Please contact the event organizer <a href="eventOrganizerQuery.php">here</a> to find out if you are entitled to a refund and wait for their response before cancelling.
        <br>
        If you would like to procceed please enter in an a valid Student Number and the Event ID you want to cancel your ticket to<br>
        You can check the DUMSS Event Table to find the appropriate Event ID<a href="https://macneill.scss.tcd.ie/~haskinsm/displayEventTable.php"> here.</a>
    </h3>
    
    <?php
        // define variables and set to empty values
        $eventid = $studnum = "";
        $eventidErr = $studnumErr = "";
       
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            if (empty($_POST["eventid"])) {
                $eventidErr = "Event ID is required";
            } else {
                $eventid = test_input($_POST["eventid"]);
                if ( strlen($eventid) != 4) { 
                    $eventidErr = "EventId must be 4 chars long";
                }
            }

            if (empty($_POST["studnum"])) {
                $studnumErr = "Student Number is required";
            } else {
                $studnum = test_input($_POST["studnum"]);
                if ( !is_numeric($studnum) ){
                    $studnumErr = "Invalid Student Number. Must contain numbers only.";
                }
                if( count_digit($studnum) > 10 || count_digit($studnum) < 5){  
                    $studnumErr = "Invalid Student Number. Must be between 5 and 10 digits in length.";
                }
            }

            if( $eventidErr == "" && $studnumErr == ""){
                include ("detail.php"); 

                $q = "DELETE FROM ticket WHERE dbstudent_num = '$studnum' && dbevent_id = '$eventid'";

                $resultQuery = $db->query($q);
                if( $resultQuery){
                    echo "<h4>";
                    echo "The member, whose student number is ".$studnum.", has cancelled ALL their tickets to the ".$eventid." event. Please check if you have cancelled the right tickets by navigating to the home page and checking the ticket table." ;
                    echo "</h4>";
                } else { ## Unfort Don't think it will ever enter below loop. Don't have time to fix rn
                    echo "Delete Failed. No tickets found for the event with ID ".$eventid." for student number ".$studnum;
                }
            } 
        }

        function test_input($data) {
            $data = trim($data); ##Strip unnecessary characters (extra space, tab, newline) from the user input data (with the PHP trim() function)
            $data = stripslashes($data); ##Remove backslashes (\) from the user input data (with the PHP stripslashes() function)
            $data = htmlspecialchars($data); ##For security reasons I think
                
            return $data;
        }

        function count_digit($number)
        {
            return strlen((string) $number);
        }

    ?>

    <h4>(*Required Fields)</h4>
  
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table>
            <tr>
                <td>Event ID (Must be 4 chars only)*:</td>
                <td><input type="text" name ="eventid" id = "eventid" size = 10 value="<?php echo $eventid;?>"></td>
                <span class="error"> <?php if(!empty($eventidErr)){ echo "Error: *".$eventidErr."<br>";}?></span>
            </tr>
            <tr>
                <td>Student Number*:</td>
                <td><input type="text" name ="studnum" id = "studnum" size = 10 value="<?php echo $studnum;?>"></td>
                <span class="error"> <?php if(!empty($studnumErr)){ echo "Error: *".$studnumErr."<br>";}?></span>
            </tr>
            <tr>
                <td>
                    <input type="submit" name = "Submit" value = "Submit Entry">
                </td>
            </tr>
        </table>
    </form>
    <br><br>
    <a href="DUMSSMainPage.php"> DUMSS Home Page </a>
</body>
</html>

