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
        h4{
            background-color: aquamarine;
            width: fit-content;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DUMSS Form Completion Page</title>
</head>
<body>
    <img src="dumms.png" alt="DUMMS" height="136" width = "193">
    <h2>DUMSS Completion Form Page</h2>

    <h4>  
        <?php
            if( !empty($_SESSION["ticket_num"]) ){ 
                echo "Thank you for ordering a ticket! Your ticket number is ";
                echo  $_SESSION["ticket_num"];
                echo ". Please keep a record of this number";
             } else{
                 echo "Thank you for completing the form! The data has been sent to the relevant DUMMS database.";
             }
                unset ($_SESSION["ticket_num"]);
        ?>
    </h4>
    <br>

    <OL>
        <li><a href="DUMSSMainPage.php"> DUMSS Home Page </a></li>
        <li><a href="studentMemberForm.php"> Student Member Registration Form </a> </li>
        <li><a href="eventForm.php"> Event Creation Form </a></li>
        <li><a href="ticketForm.php"> Ticket Form(2/2)* </a></li> 
        *If you want to order a ticket for the member with student num: <?php echo $_SESSION["studnum"]?> please follow this link. 
        <br>
        If there is no student number saved in this current session the above link will still redirect you to the correct page.
        <br>
        <li><a href="checkRegistered.php"> Ticket Form (1/2)**</a></li> 
        **If you would like to order a ticket for another member please follow this link.
        <br>  
    </OL>
</body>
</html>