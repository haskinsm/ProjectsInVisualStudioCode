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
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DUMMS Event Query</title>
</head>
<body>
    <img src="dumms.png" alt="DUMMS" height="136" width = "193"> <h2>Event Query</h2> 
    <h3>
        Enter in an existing DUMSS Event ID and Event info will be displayed. <br>
        You can check the DUMSS Event Table to find the appropriate Event ID<a href="https://macneill.scss.tcd.ie/~haskinsm/displayEventTable.php"> here.</a>
    </h3>
    
    <?php
        // define variables and set to empty values
        $eventid = "";
        $eventidErr = "";
        $eventExists = False;
        $eventNotExist = False;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            if (empty($_POST["eventid"])) {
                $eventidErr = "Event ID is required";
            } else {
                $eventid = test_input($_POST["eventid"]);
                if ( strlen($eventid) != 4) { 
                    $eventidErr = "EventId must be 4 chars long";
                }
            }

            if( $eventidErr == "" ){
                include ("detail.php"); 

                $queryEvent  = "SELECT * FROM event WHERE dbevent_id = '$eventid'";

                $resultThisQuery = $db->query($queryEvent);
                if ($resultThisQuery->num_rows > 0){
                    $eventExists = True;
                    $row = $resultThisQuery -> fetch_assoc();
                    $_SESSION["event_id"] = $row["dbevent_id"];
                    $_SESSION["organstudnum"] = $row["dborganizer_id"];
                    $_SESSION["title"] = $row["dbtitle"];
                    $_SESSION["capacity"] = $row["dbcapacity"];
                    $_SESSION["price"] = $row["dbprice"];

                    $queryTicketsSold = "SELECT COUNT(dbticket_num) AS ticket_count FROM ticket WHERE dbevent_id = '$eventid'";
                    $resultQueryTS =  $db->query($queryTicketsSold);
                    $rowTSQuery = $resultQueryTS -> fetch_assoc();
                    $_SESSION["ticketsSold"] = $rowTSQuery['ticket_count'];
                } else{
                    $eventNotExist = True;
                }

            }
        }

        function test_input($data) {
            $data = trim($data); ##Strip unnecessary characters (extra space, tab, newline) from the user input data (with the PHP trim() function)
            $data = stripslashes($data); ##Remove backslashes (\) from the user input data (with the PHP stripslashes() function)
            $data = htmlspecialchars($data); ##For security reasons 

            return $data;
        }

    ?>

    <script language="javascript">	
        // If entered event exists redirected to page where the relevant info will be displayed
        if( "<?php echo $eventExists ?>" ){
             document.location.replace("eventCountInfo.php");
        } else if( "<?php echo  $eventNotExist; ?>" ){ // If event does not exist will display a h4 header error message
            document.write("<h4> Error: Please Enter in an existing Event. To see a list of events go to the home page and select display events link.")
        }

    </script>

    <h4>(*Required Fields)</h4>
  
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table>
            <tr>
                <td>Event ID (Must be 4 chars only)*:</td>
                <td><input type="text" name ="eventid" id = "eventid" size = 10 value="<?php echo $eventid;?>"></td>
                <span class="error"> <?php if(!empty($eventidErr)){ echo "Error: *".$eventidErr."<br>";}?></span>
            </tr>
            <tr>
                <td>
                    <input type="submit" name = "Submit" value = "Submit Entry">
                </td>
            </tr>
        </table>
    </form>
    <br><br><br><br><br>
    <a href="DUMSSMainPage.php"> DUMSS Home Page </a>
</body>
</html>

