<!--
    Purpose of Script: Customer sign up page
    Written by: Harry O'Brien
    last updated: Harry 20/2/21
    Source: https://www.tutorialspoint.com/php/php_mysql_login.htm
-->

<?php
   include("ServerDetail.php");
   session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // Email and password sent from form 
      
      $myEmail = mysqli_real_escape_string($db,$_POST['Email']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
      
      $sql = "SELECT * FROM Passwords WHERE Email = '$myEmail' and password = '$mypassword'";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $active = $row['active'];
      
      $count = mysqli_num_rows($result);
      
      // If result matched $myEmail and $mypassword, table row must be 1 row
		
      if($count == 1) {
         session_register("myEmail");
         $_SESSION['login_user'] = $myEmail;
         
         header("location: CustomerHomePage.php");
      }else {
         $error = "Your Login Name or Password is invalid";
      }
   }
?>
<html>
   
   <head>
      <title>Login Page</title>
      
      <style type = "text/css">
         body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
         }
         label {
            font-weight:bold;
            width:100px;
            font-size:14px;
         }
         .box {
            border:#666666 solid 1px;
         }
      </style>
      
   </head>
   
   <body bgcolor = "#FFFFFF">
	
      <div align = "center">
         <div style = "width:300px; border: solid 1px #333333; " align = "left">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>
				
            <div style = "margin:30px">
               
               <form action = "" method = "post">
                  <label>Email  :</label><input type = "text" name = "Email" class = "box"/><br /><br />
                  <label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />
                  <input type = "submit" value = " Submit "/><br />
               </form>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
					
            </div>
				
         </div>
			
      </div>

   </body>
</html>
