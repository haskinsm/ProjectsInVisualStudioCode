
<!--
    Purpose of Script: staff Login,
    Written by: Harry O'Brien
    last updated: Harry 3/3/21
    Source for Login form: https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php-->

<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["Position"]) && $_SESSION["Position"] === Worker){
    header("location: StaffHomePage.php");
    exit;
}
 
// Include database connect file
require_once "ServerDetail.php";
 
// Define variables and initialize with empty values
$Email = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["Email"]))){
        $username_err = "Please enter Email.";
    } else{
        $Email = trim($_POST["Email"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT Email,Firstname, Password FROM Passwords WHERE Email = ? AND Position = 'Worker'";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_Email);
            
            // Set parameters
            $param_Email = $Email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if Email exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $Email,$Firstname, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["Email"] = $Email;
			    $_SESSION["Firstname"] = $Firstname;
			    $_SESSION["Position"] = Worker;                            
                            
                            // Redirect user to welcome page
                            header("location: StaffHomePage.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if Email doesn't exist
                    $username_err = "No account found with that email.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
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
    <title>Login</title>
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

<?php include 'UniversalMenuBar.php';?> <!-- Imports code for menu bar from another php file-->
    <div class="wrapper">
        <h2>Staff Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="Email" class="form-control" value="<?php echo $Email; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
           
        </form>
    </div>    
</body>
</html>