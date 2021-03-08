<!-- 
    Purpose of Script: Manager sign up page
    Written by: Harry O'Brien
    last updated: Harry 3/3/21
    Jason 4/3/21
    Added manager menu bar
-->

<?php
// Include database connect file
require_once "ServerDetail.php";

// Define variables and initialize with empty values
$Email = $password = $confirm_password = $firstname="";
$username_err = $password_err = $confirm_password_err = $firstname_err="";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate Email
    if(empty(trim($_POST["Email"]))){
        $username_err = "Please enter a Email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT Email FROM Passwords WHERE Email = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["Email"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This email is already taken.";
                } else{
                    $Email = trim($_POST["Email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

     //Validate firstname
     if(empty(trim($_POST["firstname"]))){
        $firstname_err = "Please enter your name.";
     } else {
	$firstname=trim($_POST['firstname']);
     }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($firstname_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO Passwords (Email,Firstname, password, position) VALUES (?,?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_username,$param_firstname, $param_password, $param_customer);

            // Set parameters
            $param_username = $Email;
	    $param_firstname=$firstname;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
	    $param_customer = 'Manager';

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                 session_start();
                            
                 // Store data in session variables
                 $_SESSION["loggedin"] = true;
                 $_SESSION["Email"] = $Email; 
		 $_SESSION["Firstname"] = $firstname;
		 $_SESSION["Position"] = Manager; 
                header("location: AddManagerInfo.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
    body { 
/* background-color: plum; /* Colour of background*/
color: rgb(119, 17, 252); /* Colour of body text*/
background-image: 
    linear-gradient(              /* This code is used to tint the background image to ensure that text is visible */
        rgba(0, 0, 0, 0.5),     /* source: https://css-tricks.com/design-considerations-text-images/ */
        rgba(0, 0, 0, 0.5)
    ),
    url("images/Background4.jpg"); /* image background */
background-repeat: no-repeat; /* image only used once */
background-size: cover;
color: white;
font: 20px sans-serif;
}

        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
 <?php include 'UniversalMenuBar.php';
 echo '<br>';
include 'ManagerMenuBar.php';?> <!-- Imports code for menu bar from another php file-->
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="Email" class="form-control" value="<?php echo $Email; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
	     <div class="form-group <?php echo (!empty($firstname_err)) ? 'has-error' : ''; ?>">
                <label>First Name</label>
                <input type="text" name="firstname" class="form-control" value="<?php echo $firstname; ?>">
                <span class="help-block"><?php echo $firstname_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="ManagementLogin.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
