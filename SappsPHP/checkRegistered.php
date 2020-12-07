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
    <title>DUMMS Check Registered Member Form</title>
</head>
<body>
    <img src="dumms.png" alt="DUMMS" height="136" width = "193"> <h2>Ticket Form(1/2): Check if Registered Member</h2> 
    <h3>
        If you are a registered member you will be brought to the final ticket form, if not you will be brought to <br>
        the membership registration form where you will need to register first.
    </h3>
    
    <?php
        // define variables and set to empty values
        $eventid = $studnum = $ticketnum = "";
        $eventidErr = $studnumErr = $ticketnumErr = "";
        $registeredMember = False;
        $unregisteredMember = False;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
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

            if( $studnumErr == "" ){
                include ("detail.php"); 
                
                $_SESSION["studnum"] = $studnum; ##Creates and sets session variable

                $queryMember  = "SELECT * FROM member WHERE dbstud_num = '$studnum'";

                $result = $db->query($queryMember);
                $total_num_rows = $result->num_rows;
                if($total_num_rows > 0){
                    ##found
                    $registeredMember = True;
                } else{
                    ##NotFound
                    $unregisteredMember = True;
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

        function count_digit($number)
        {
            return strlen((string) $number);
        }

    ?>

    <script language="javascript">	
        
        if( "<?php echo  $registeredMember ?>"){
             document.location.replace("ticketForm.php");
        } else if( "<?php echo  $unregisteredMember ?>"){
             document.location.replace("studentMemberForm.php");
        }

    </script>


    <h4>Membership Check (*Required Fields)</h4>
  
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table>
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
    <br><br><br><br><br>
    <a href="DUMSSMainPage.php"> DUMSS Home Page </a>
</body>
</html>

