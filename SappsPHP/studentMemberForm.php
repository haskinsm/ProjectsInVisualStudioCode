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
    <title>DUMMS Member form</title>
</head>
<body>
    <img src="dumms.png" alt="DUMMS" height="136" width = "193"> <h2>DUMMS Society Membership Form</h2>
    
    <?php
        // define variables and set to empty values
        $studnum = $fullname = $email = $fulltimestud = $gender = $faculty = "";
        $studnumErr = $fullnameErr = $emailErr = $fulltimestudErr = $genderErr = $facultyErr = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        	if (empty($_POST["fullname"])) {
                $fullnameErr = "Full name is required";
            } else {
                $fullname = test_input($_POST["fullname"]);
                // check if name only contains letters and whitespace
                if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) { ##The preg_match() function searches a string for pattern, returning true if the pattern exists, and false otherwise.
                    $fullnameErr = "Only letters and white space allowed";
                }
            }
            
            if (empty($_POST["email"])) {
                $emailErr = "Email is required";
            } else {
                $email = test_input($_POST["email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid email format";
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
        
            if (empty($_POST["fulltime"])) {
                $fulltimestudErr = "Must Select either yes or no for full time student";
            } else {
                $fulltimestud = test_input($_POST["fulltime"]);
            }
        
            if (empty($_POST["gender"])) {
                $genderErr = "Must Select one gender";
            } else {
                $gender = test_input($_POST["gender"]);
            }

            if (empty($_POST["faculty"])) {
                $facultyErr = "Must Select one faculty";
            } else {
                $faculty = test_input($_POST["faculty"]);
            }

            if( $studnumErr == "" && $fullnameErr == "" && $emailErr == "" && $fulltimestudErr == "" && $genderErr == "" && $facultyErr == ""){
                include ("detail.php"); 

                $dataSubmitted = True;
                ## Might need to rewrite below as $sql 


                $q  = "INSERT INTO member (";
                $q .= "dbstud_num, dbfull_name, dbemail, dbfulltime_stud, dbgender, dbschool";
                $q .= ") VALUES (";
                $q .= "'$studnum', '$fullname', '$email', '$fulltimestud', '$gender', '$faculty')";

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

        function count_digit($number)
        {
            return strlen((string) $number);
        }

    ?>

    <script language="javascript">	
        
        if( "<?php echo $dataSubmitted ?>"){ 
             document.location.replace("FormCompletion.htm");
        }

    </script>

    <h4>Member Details (*Required Fields)</h4>
   <!-- Will need to post it to another php yoke that I will have to create
        Might have to use a php if statement to see if have any erros if do stays in this doc, if not goes to other php doc -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table>
            <tr>
                <td>Full name*:</td>
                <td><input type="text" name ="fullname" id = "fullname" size = 40 value="<?php echo $fullname;?>"></td>
                <span class="error"> <?php if(!empty($fullnameErr)){ echo "Error: *".$fullnameErr."<br>";}?></span>
            </tr>
            <tr>
                <td>Trinity Email*:</td>
                <td><input type="text" name ="email" id = "email" size = 50 value="<?php echo $email;?>"></td>
                <span class="error"> <?php if(!empty($emailErr)){ echo "Error: *".$emailErr."<br>";}?></span>
            </tr>
            <tr>
                <td>Student Number*:</td>
                <td><input type="text" name ="studnum" id = "studnum" size = 10 value="<?php echo $studnum;?>"></td>
                <span class="error"> <?php if(!empty($studnumErr)){ echo "Error: *".$studnumErr."<br>";}?></span>
            </tr>
            <tr>
                <td>Full time student*:</td>
                <td>
                    <input type="radio" name ="fulltime" id = "Yes" <?php if (isset($fulltimestud) && $fulltimestud=="Yes") echo "checked";?> value="Yes">Yes
                    <input type="radio" name ="fulltime" id = "No" <?php if (isset($fulltimestud) && $fulltimestud=="No") echo "checked";?> value="No" >No
                    <!-- The php bit here is necessary to store the results when user clicks submit, so will still be there if an error in one field-->
                </td>
                <span class="error"> <?php if(!empty($fulltimestudErr)){ echo "Error: *".$fulltimestudErr."<br>";}?></span>
            </tr>
            <tr>
                <td>Gender*:</td>
                <td>
                    <input type="radio" name ="gender" id = "Male" <?php if (isset($gender) && $gender=="Male") echo "checked";?> value="Male">Male
                    <input type="radio" name ="gender" id = "Female" <?php if (isset($gender) && $gender=="Female") echo "checked";?> value="Female" >Female
                    <!-- The php bit here is necessary to store the results when user clicks submit, so will still be there if an error in one field-->
                </td>
                <span class="error"> <?php if(!empty($genderErr)){ echo "Error: *".$genderErr."<br>";}?></span>
            </tr>
            <tr>
                <td>TCD School*:</td>
                <td>
                    <input type="radio" name ="faculty" id = "SCSS" <?php if (isset($faculty) && $faculty=="SCSS") echo "checked";?> value="SCSS">SCSS
                    <input type="radio" name ="faculty" id = "BUSS" <?php if (isset($faculty) && $faculty=="BUSS") echo "checked";?> value="BUSS" >BUSS
                    <input type="radio" name ="faculty" id = "ARTS" <?php if (isset($faculty) && $faculty=="ARTS") echo "checked";?> value="ARTS">ARTS
                    <input type="radio" name ="faculty" id = "SCI" <?php if (isset($faculty) && $faculty=="SCI") echo "checked";?> value="SCI">SCI
                    <input type="radio" name ="faculty" id = "MathsandENG" <?php if (isset($faculty) && $faculty=="MathsandENG") echo "checked";?> value="MathsandENG">MathsandENG
                    <!-- The php bit here is necessary to store the results when user clicks submit, so will still be there if an error in one field-->
                </td>
                <span class="error"> <?php if(!empty($facultyErr)){ echo "Error: *".$facultyErr."<br>";}?></span>
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

