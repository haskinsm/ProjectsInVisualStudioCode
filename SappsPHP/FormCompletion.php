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
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DUMSS Form Completion Page</title>
</head>
<body>
    <img src="dumms.png" alt="DUMMS" height="136" width = "193">
    <h2>DUMSS Completion Form Page</h2>

    <h4> Thank you for completing the form! The data has been sent to the relevant DUMMS database.</h4>
    <h4>  
        <?php
            if( !empty($_SESSION["ticket_num"]) ){ 
                echo "Thank you for ordering a ticket! Your ticket number is ";
                echo  $_SESSION["ticket_num"];
                echo ". Please keep a record of this number"; }
                unset ($_SESSION["ticket_num"]);
        ?>
    </h4>
    <br>

    <OL>
        <li><a href="DUMSSMainPage.php"> DUMSS Home Page </a></li>
        <li><a href="studentMemberForm.php"> Student Member Registration Form </a> </li>
        <li><a href="eventForm.php"> Event Creation Form </a></li>
        <li><a href="ticketForm.php"> Ticket Form(2/2)* </a></li> 
        *(If you have just completed the ticket or member registration form and <br>
        want to order a ticket for the SAME MEMBER (i.e. Same Student Number) please click the 'Ticket Form' link <br>
        which will take you to the second ticket Form where you only need to enter the event ID, you will not need to <br>
        complete the first ticket form as your student number is currently saved in this session)
        <br>
        <li><a href="checkRegistered.php"> Ticket Form (1/2)**</a></li> 
        **If you have just completed the Event Form and want to register for a ticket or want to get a ticket for ANOTHER MEMBER (i.e. Different Student Number) <br>
        please click the following link: <br>
        <br>  
    </OL>
</body>
</html>