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
    <img src="dumms.png" alt="DUMMS" height="136" width = "193"> 
    <h2>Event Organizer Query</h2> 
    <h3>
        Enter in an existing DUMSS Event ID and Event Organizer info will be displayed
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

                $queryEv = "SELECT dbfull_name, dbemail, dbtitle, dbdate FROM event, member ";
                $queryEv .= "WHERE dbstud_num = dborganizer_id && dbevent_id = '$eventid'"; 
                  ## Due to the way data is entered the situation would never arise where a LEFT or RIGHT join would function any different
                  ## to the EQUI-JOIN implemented here

                $resultQuery = $db->query($queryEv);
                if ($resultQuery->num_rows > 0){
                    $eventExists = True;
                    $row = $resultQuery -> fetch_assoc();
                    $_SESSION["full_name"] = $row["dbfull_name"];
                    $_SESSION["email"] = $row["dbemail"];
                    $_SESSION["title"] = $row["dbtitle"];
                    $_SESSION["date"] = $row["dbdate"];
            
                } else{
                    $eventNotExist = True;
                }

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
        
        if( "<?php echo $eventExists ?>" ){
             document.location.replace("eventOrganizerInfo.php");
        } else if( "<?php echo  $eventNotExist; ?>" ){
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

