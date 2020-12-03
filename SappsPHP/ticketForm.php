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
    <title>DUMMS Ticket form</title>
</head>
<body>
    <img src="dumms.png" alt="DUMMS" height="136" width = "193"> <h2>DUMMS Ticket Form</h2>
    
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
            
            if (empty($_POST["studnum"])) {
                $studnumErr = "Student Number is required";
            } else {
                $studnum = test_input($_POST["studnum"]);
                if ( !is_numeric($studnum) ){
                    $studnumErr = "Invalid Student Number. Must contain numbers only.";
                }
            }
        
            if (empty($_POST["ticketnum"])) {
                $ticketnumErr = "Must enter the ticket number";
            } else {
                $ticketnum = test_input($_POST["ticketnum"]);
                if ( !is_numeric($ticketnum) ){
                    $ticketnumErr = "Invalid Ticket Number. Must contain numbers only.";
                }
            }

            if( $eventidErr == "" && $fullnameErr == "" && $ticketnumErr == "" ){
                include ("detail.php"); 

                $dataSubmitted = True;
                ## Might need to rewrite below as $sql 


                $q  = "INSERT INTO ticket (";
                $q .= "dbticket_num, dbstudent_num, dbevent_id";
                $q .= ") VALUES (";
                $q .= "'$ticketnum', '$studnum', '$eventid')";

                $result = $db->query($q);

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
        
        if( "<?php echo $dataSubmitted ?>"){
             document.location.replace("FormCompletion.htm");
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
                <td>Student Number*:</td>
                <td><input type="text" name ="studnum" id = "studnum" size = 10 value="<?php echo $studnum;?>"></td>
                <span class="error"> <?php if(!empty($studnumErr)){ echo "Error: *".$studnumErr."<br>";}?></span>
            </tr>
            <tr>
                <td>Ticket Number*:</td>
                <td><input type="text" name ="ticketnum" id = "ticketnum" size = 10 value="<?php echo $ticketnum;?>"></td>
                <span class="error"> <?php if(!empty($ticketnumErr)){ echo "Error: *".$ticketnumErr."<br>";}?></span>
            </tr>
          
            <tr>
                <td>
                    <input type="submit" name = "Submit" value = "Submit Form to database">
                </td>
            </tr>
        </table>
    </form>
    <br><br><br><br><br>
    <a href="DUMMSMainPage.html"> DUMMS Home Page </a>
</body>
</html>

