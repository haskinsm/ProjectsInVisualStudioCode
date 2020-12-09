
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
    <title>DUMMS Event Organizer Query</title>
</head>
<body>
    <img src="dumms.png" alt="DUMMS" height="136" width = "193"> 
    
    <h2>Event Organizer Query</h2> 

    <h3>
        Enter in an existing DUMSS Event ID and Event Organizer info will be displayed
    </h3>

    <?php 
         $eventid = "";
         if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $eventid = test_input($_POST['eventIDSelected']);

            if( strlen($eventid) > 0){
                echo $eventid;
                echo "kkkjj";
                include ("detail.php"); 

                $queryE = "SELECT * FROM event";
               ## $queryE = "SELECT dbfull_name, dbemail, dbtitle, dbdate FROM event, member ";
              ##  $queryE .= "WHERE dbstud_num = dborganizer_id && dbevent_id = '$eventid'"; 
                ## Due to the way data is entered the situation would never arise where a LEFT or RIGHT join would function any different
                ## to the EQUI-JOIN implemented here
                echo "hh".$eventid;
                $resultQueryE = $db->query($queryE);
                echo $resultQueryE;
                echo $eventid;
                if ($resultQueryE->num_rows > 0){
                    echo "HEREE";
                    /*
                    $row = $resultQueryE -> fetch_assoc();

                    echo '<p>';

                    echo "The ogranizer of this event is ".$row["dbfull_name"];
                    echo '<br>'."The title of this event is ".$row["dbtitle"];
                    echo '<br>'."The event will be held on the ".$row["dbdate"];
                    echo '<br>'."Please contact the organizer by email ".'<a href="malito:"'.$row["dbemail"].'>here</a>';

                    echo '</p>'; */
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
  
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table>
            <tr>
                <td class = "select">List of DUMSS Events </td>
                <td>
                    <select id="eventIDSelected" name="eventIDSelected">
                        <?php 
                            include ("detail.php"); 

                            $DBQuery  = "SELECT dbevent_id FROM event";
            
                            $result = $db->query($DBQuery);

                            while ( $row = $result -> fetch_assoc()){
                            
                                echo '<option value="'.$row["dbevent_id"].'">'.$row["dbevent_id"].'</option>'; ##Might have error here with ""
                                ## Want: <option value="eventName">eventName</option>
                            }
                            
                        ?>
                    </select>
                </td>             
            </tr>
            <tr>
                <td>
                    <input type="submit" name = "Submit" value = "Submit">
                </td>
            </tr>
        </table>
    </form>

    <br><br><br>
    <a href="DUMSSMainPage.php"> DUMSS Home Page </a>
    <br>

</body>
</html>

