<!--
    Purpose of Script: Add Manager Info to employee table
    Written by: Harry O'Brien
    last updated: Harry 08/03/21
    Source for Info form: AddCustomerInfo.php -->


<!DOCTYPE html>
<html>
<head>
   <title>Website</title> <!-- bootstrapCDN -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

   <style type="text/css">
       body { 
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
    	font-size: 20px;
	}

       .list-group-item {
           background-color: none !important; 
           border: none !important; 
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        input[type=number] {
        -moz-appearance: textfield;
        }
   </style> 

</head>
<?php 
     session_start();
     include "ServerDetail.php"; //Checks if 
     if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["Position"]) && $_SESSION["Position"] === Manager)){
    	header("location: ManagementLogin.php");
    	exit;
     }

?>
<body>

    <div class="container">

        <?php  include "UniversalMenuBar.php";  ?>
	<br>

        <?php include 'ManagerMenuBar.php';?> <!-- Imports code for manager menu bar from another php file-->
        <br>

        <?php
            if(isset($_GET['emailExist'])){
        ?>
            <div class="form-group">
                <div class="alert alert-danger alert-dismissible" data-auto-dismiss role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <p>Email Already In Use</p>
                    <p>Please choose a different Email</p>
                </div>
            </div>       
        <?php
            }
	    else {
		 global $link;

   		 $Email=$_SESSION['Email'];
		 $name="";
		 $wage="";
		 $update=false;
		 $sql = "SELECT * FROM Employees WHERE Empl_Email = '$Email'";
    		 $result = mysqli_query($link,$sql);
		 if(mysqli_num_rows($result) > 0){

     		    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		    $name=$row['Worker_Name'];
		    $wage=$row['Wage_Per_Hour'];
		    $update=true;
		}
	    }

        ?>

        <div class="row" style="padding-top: 20px;padding-bottom: 20px;">
        
           <div class="col-lg-3 col-md-3">
                 
           </div>
           <div class="col-lg-9 col-md-9">   
                 <h3 class="text-success">
                        Add Manager Details.<br>
                 </h3>
                 <p>
                      Please fill in your details below. <br>
                 </p>
                
                 <form method="post" action="ManagerInfoCode.php">

                    <div class="row">
                        <div class="col-lg-2 col-md-2">
                            Full Name:
                        </div>
                        <div class="col-lg-8 col-md-8">
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>"
                                   required="required">
                        </div>
                    </div>
                 
                    <div class="row">
                        <div class="col-lg-2 col-md-2">
                            Wage per hour:
                        </div>
                        <div class="col-lg-8 col-md-8">
                            <input type="number" step=".01" class="form-control" id="wage" name="wage"  value="<?php echo $wage; ?>"
                                   required="required">
                        </div>
                    </div>
                                        <div class="row" style="padding-top:20px">
                        <div class="col-lg-4 col-md-4">
                            
                        </div>
                        <div class="col-lg-8 col-md-8">
                            <?php if ($update){ ?>
				<input type="submit" id="updateManagerInfoBtn" name="updateManagerInfoBtn" value="Update my details">
			    <?php }
			    else { ?>
                            	<input type="submit" id="addManagerInfoBtn" name="addManagerInfoBtn" value="Add my details">
			    <?php } ?>
                        </div>
                    </div>


                 </form>

           </div>
        </div>

    </div>

</body>
</html>