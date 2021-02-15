<!-- 
    Purpose of Script: Management Login
    Written by: Michael H
    last updated: Michael 12/02/21
    Source for Login form: https://www.w3schools.com/howto/howto_css_login_form.asp
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Management Login </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> <!-- All CSS should be added to the WebsiteStyle.css file and then it will be imported here. If want a unique 
            style for something should be done in line like so: E.G:   <h1 style="color:blue;text-align:center;">  This is a heading </h1>       -->
    <style>
    form {border: 3px solid #f1f1f1;} /* Formatting for form */

    /* Format for the input fields */
    input[type=text], input[type=password] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
    }

    button {
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
    }

    /* Makes button change colour when hover mouse over it */
    button:hover {
    opacity: 0.8;
    }

    /* Formatiing for create account button */
    .newAccountbtn {
    width: auto;
    padding: 10px 18px;
    background-color: blue;
    }
    /* Formats the manager avatar image */
    .imgcontainer {
    text-align: center;
    margin: 24px 0 12px 0;
    }
    /* Scales the managaer avatar image */
    img.avatar {
    width: 20%;
    border-radius: 50%;
    }

    .container {
    padding: 16px;
    }
    /* This is the formatting for the forgot password bit */
    span.psw {
    float: right;
    padding-top: 16px;
    }

    /* Change styles for span and cancel button on extra small screens */
     @media screen and (max-width: 300px) {
        span.psw {
            display: block;
            float: none;
        }
        .cancelbtn {
            width: 100%;
        }
    }
    /* visited link styling */
    a:visited {
    color: black;
    }
    </style>
</head>


<body>

    <?php include 'UniversalMenuBar.php';?> <!-- Imports code for menu bar from another php file-->

    <h2>Login Form</h2>

    <!-- Below is the code for the input form -->
    <form action="ManagerHomePage.php" > <!-- Currently does not check if valid password & email combo, just redirects as long as something is entered -->
    <!--
        Will prob have to do something like 
        <form action = "myFunction()" >
        <script>
        function myFunction() {
            ***Code to check if password and emial belongs to a valid user***
            window.location.href="ManagerHomePage.php";
        }
        </script>
    -->
    <div class="imgcontainer">
        <img src="images/ManagerAvatar.jpg" alt="Avatar" class="avatar"> 
    </div>

    <div class="container">
        <label for="email"><b>Email</b></label>
        <input type="text" placeholder="Enter Email" name="email" required>

        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="psw" required>
            
        <button type="submit" >Login</button>
        <span class="psw"> <a href="##" >Forgot password?</a></span>   <!-- Can ask if they want this, if do will need to add a link to a page where they can enter their manager id and then have their password emailed to them by a malito link (The email one) ***************************************************-->
    </div>

    <div class="container" style="background-color:#f1f1f1">
       <!-- <button type="button" class="newAccountbtn">Create Account</button>  -->
    
    </div>
    </form>

</body>
</html>