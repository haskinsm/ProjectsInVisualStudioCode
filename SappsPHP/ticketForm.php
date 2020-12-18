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
        h4{
            background-color: aquamarine;
            width: fit-content;
        }
        .tab { 
            display:inline-block; 
            margin-left: 40px; 
        }
        .error {
            background-color: red;
            width: fit-content;
            font-weight: bold;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DUMSS Ticket form</title>
</head>
<body>
    <img src="dumms.png" alt="DUMMS" height="136" width = "193"> 
    
    <h2>DUMSS Ticket Form (2/2) <span class="tab"> <a href="DUMSSMainPage.php"> DUMSS Home Page </a> </span> </h2>
    
    <?php
        // define variables and set to empty values
        $eventid = $studnum = $ticketnum = "";
        $eventidErr = $studnumErr = $ticketnumErr = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            if (empty($_POST["eventid"])) {
                $eventidErr = "Event ID is required";
            } else {
                $eventid = test_input($_POST["eventid"]);
                if ( strlen($eventid) != 4) { 
                    $eventidErr = "EventId must be 4 chars long";
                }
            }

            if( $eventidErr == "" ){ ## Check if event exists with entered event id
                include ("detail.php"); 

                $queryEvent  = "SELECT * FROM event WHERE dbevent_id = '$eventid'";

                $resultThisQuery = $db->query($queryEvent);
                if ($resultThisQuery->num_rows > 0){
                    ## $eventExists = True;                 
                } else{
                    $eventidErr = "No event exists with the event ID you have entered. Please check the ";
                }
            }

            if( $eventidErr == "" && $studnumErr == "" && $ticketnumErr == "" ){
                include ("detail.php"); 

                $dataSubmitted = True;
                
                ## On first pass through this doc will enter here and session var will be set
                if( $studnum > 0){
                    $_SESSION["studnum"] = $studnum;
                }
                $studentNumberCurrentSession = $_SESSION["studnum"]; ## On Second pass it becomes essential that session var is used instead of $studnum
                ## Need this as can't directly put $_SESSION["studnum"] in below SQL statement

                $q  = "INSERT INTO ticket (";
                $q .= "dbticket_num, dbstudent_num, dbevent_id";
                $q .= ") VALUES (";
                $q .= "'$ticketnum', '$studentNumberCurrentSession', '$eventid')"; ##ticketNum is auto-incremeneted in db, its value here doesnt matter (its 0 or "")

                $result = $db->query($q);

                ## Below code is used to give the user their ticket number in following page. 
                ## The most recently generated ticket num for that student id is returned
                $myQuery = "SELECT * FROM ticket WHERE dbstudent_num = '$studentNumberCurrentSession'";
                $resultMyQuery = $db->query($myQuery);
               
                $lastTicketNum = 0;
                if ($resultMyQuery->num_rows > 0){
                  while($row = $resultMyQuery -> fetch_assoc()){ ##Purpose of this loop is to return most recently generated ticket num relating to a studnum
                      $lastTicketNum = $row["dbticket_num"];
                  }
                }
                $_SESSION["ticket_num"] = $lastTicketNum;
               
            }
        }

        function test_input($data) {
            $data = trim($data); ##Strip unnecessary characters (extra space, tab, newline) from the user input data (with the PHP trim() function)
            $data = stripslashes($data); ##Remove backslashes (\) from the user input data (with the PHP stripslashes() function)
            $data = htmlspecialchars($data); ##For security reasons I think
                ##When we use the htmlspecialchars() function; then if a user tries to submit the following in a text field:
                ## <script>location.href('http://www.hacked.com')</script>
                
                ##- this would not be executed, because it would be saved as HTML escaped code, like this:
                
                ## &lt;script&gt;location.href('http://www.hacked.com')&lt;/script&gt;
                
                 ##The code is now safe to be displayed on a page or inside an e-mail.
            return $data;
        }

    ?>

    <script language="javascript">	
        // Redirects userr to form completion page where ticket number is issued
        if( "<?php echo $dataSubmitted ?>"){
             document.location.replace("FormCompletion.php");
        }
        // If user has entered from the display event table they skip the first form so may have no registered 
        // student num in this session.
        // If this is the case,this condition will redirect them to the first form where it is checked if 
        // they are a registered member and the stud num is taken in and stored.
        if( "<?php echo $studnum ?>" == "" && "<?php echo $_SESSION["studnum"] ?>" == ""){ 
             document.location.replace("checkRegistered.php");
        }
    </script>

    <h4>  
        You can check the DUMSS Event Table to find the appropriate Event ID<a href="displayEventTable.php"> here.</a>
        <br>        
        Event Details (*Required Fields)
    </h4>
  
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table>
            <tr>
                <td>Event ID (Must be 4 chars only)*:</td>
                <td><input type="text" name ="eventid" id = "eventid" size = 10 value="<?php echo $eventid;?>"></td>
                <span class="error"> <?php if(!empty($eventidErr)){ echo "Error: *".$eventidErr."<br>";}?></span>
            </tr>
            <tr>
                <td>
                    <input type="submit" name = "Submit" value = "Submit Form to database">
                </td>
            </tr>
        </table>
    </form>
    <br><br>
    <a href="DUMSSMainPage.php"> DUMSS Home Page </a>
</body>
</html>

