<?php
// Start the session
session_start();
## Now destroy session and session variables
unset ($_SESSION["studnum"]);
$_SESSION = array(); 
## To unset all at once: $_SESSION = array();
session_destroy();
##Did this as can hit problems when users hit home page after completing forms so want to reset at the home page.
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
    <title>DUMSS Home Page</title>
</head>
<body>
    <img src="dumms.png" alt="DUMMS" height="136" width = "193"> <h2>DUMSS Main Page</h2>

    <p>Hello and welcome to DUMSS! </p>
    <OL> 
        <li>Forms: </li>
           <OL>
                <li><a href="studentMemberForm.php"> Student Member Registration Form</a> </li>
                <li><a href="eventForm.php"> Event Creation Form</a></li>
                <li><a href="checkRegistered.php"> Ticket Form (1/2)</a></li>
           </OL>
        <li>Querys: </li>
            <OL>
                <li><a href="eventCountQuery.php"> Tickets Sold to Event Query</a></li>
                <li><a href="eventOrganizerQuery.php"> Contact Event Organizer </a></li>
                <li><a href="cancelTicket.php"> Cancel Ticket</a></li>
            </OL>
        <li>Reports: </li>
            <OL>
                <li><a href="displayTicketTable.php"> Displays all data in ticket table</a></li>
                <li><a href="displayEventTable.php"> Displays all data in event table</a></li>
                <li><a href="displayMemberTable.php"> Displays all data in member table</a></li>
            </OL>
    </OL>
</body>
</html>