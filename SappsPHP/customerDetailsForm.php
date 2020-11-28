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
    <title>Lloyd Customer Details Form</title>
</head>
<body>
    <img src="Cheese.jpg" alt="cheese" height="136" width = "193"> <h2>Lloyd Cheese Company</h2>
    
    <?php
        // define variables and set to empty values
        $name = $custemail = $compname = $compaddress = $onaccount = "";
        $nameErr = $custemailErr = $compnameErr = $compaddressErr = $onaccountErr = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        	if (empty($_POST["custname"])) {
                $nameErr = "Name is required";
            } else {
                $name = test_input($_POST["custname"]);
                // check if name only contains letters and whitespace
                if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) { ##The preg_match() function searches a string for pattern, returning true if the pattern exists, and false otherwise.
                    $nameErr = "Only letters and white space allowed";
                }
            }
            
            if (empty($_POST["custemail"])) {
                $custemailErr = "Email is required";
            } else {
                $custemail = test_input($_POST["custemail"]);
                if (!filter_var($custemail, FILTER_VALIDATE_EMAIL)) {
                    $custemailErr = "Invalid email format";
                }
            }
            
            if (empty($_POST["compaddress"])) {
                $compaddressErr = "";
            } else {
                $compaddress = test_input($_POST["compaddress"]);
            }
        
            if (empty($_POST["onaccount"])) {
                $onaccountErr = "Must Select either yes or no for on account";
            } else {
                $onaccount = test_input($_POST["onaccount"]);
            }
        
            if (empty($_POST["compname"])) {
                $compnameErr = "";
            } else {
                $compname = test_input($_POST["compname"]);
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
    <h4>Customer Details (*Required Fields)</h4>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table>
            <tr>
                <td>Name*:</td>
                <td><input type="text" name ="custname" id = "custname" size = 25 value="<?php echo $name;?>"></td>
                <span class="error"> <?php if(!empty($nameErr)){ echo "Error: *".$nameErr."<br>";}?></span>
            </tr>
            <tr>
                <td>Email*:</td>
                <td><input type="text" name ="custemail" id = "custemail" size = 25 value="<?php echo $custemail;?>"></td>
                <span class="error"> <?php if(!empty($custemailErr)){ echo "Error: *".$custemailErr."<br>";}?></span>
            </tr>
            <tr>
                <td>Company Name:</td>
                <td><input type="text" name ="compname" id = "compname" size = 25 value="<?php echo $compname;?>"></td>
            </tr>
            <tr>
                <td>Company Address:</td>
                <td><input type="text" name ="compaddr" id = "compaddr" size = 45 value="<?php echo $compaddress;?>"></td>
            </tr>
            <tr>
                <td>On Account*:</td>
                <td>
                    <input type="radio" name ="onaccount" id = "yes" <?php if (isset($onaccount) && $onaccount=="yes") echo "checked";?> value="yes">Yes 
                    <input type="radio" name ="onaccount" id = "no" <?php if (isset($onaccount) && $onaccount=="no") echo "checked";?> value="no" >No
                    <!-- The php bit here is necessary to store the results when user clicks submit, so will still be there if an error in one field-->
                </td>
                <span class="error"> <?php if(!empty($onaccountErr)){ echo "Error: *".$onaccountErr."<br>";}?></span>
            </tr>
            <tr>
                <td>
                    <input type="submit" name = "Submit" value = "Submit Order">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>

