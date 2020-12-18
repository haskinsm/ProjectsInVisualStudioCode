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
        h3{
            background-color: pink;
            width: fit-content;
        }
        h4{
            background-color: aquamarine;
            width: fit-content;
        }
        .tab { 
            display:inline-block; 
            margin-left: 40px; 
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DUMMS Event Query</title>
</head>
<body>
    <img src="dumms.png" alt="DUMMS" height="136" width = "193"> 
    
    <h2>Event Query <span class="tab"> <a href="DUMSSMainPage.php"> DUMSS Home Page </a> </span> </h2> 

    <h4>Please Find Event info below:</h4>
  
        <table>
            <tr>
                <td>Event ID: </td>
                <td> <?php echo $_SESSION["event_id"] ?> </td>
            </tr>
            <tr>
                <td>Organizer's Student Number: </td>
                <td> <?php echo  $_SESSION["organstudnum"]  ?> </td>
            </tr>
            <tr>
                <td>Event Title: </td>
                <td> <?php echo $_SESSION["title"]  ?> </td>
            </tr>
            <tr>
                <td>Event Capacity: </td>
                <td> <?php echo $_SESSION["capacity"]  ?> </td>
            </tr>
            <tr>
                <td>Tickets Sold: </td>
                <td> <?php echo $_SESSION["ticketsSold"]  ?> </td>
            </tr>
            <tr>
                <td>Event ticket revenue: </td>
                <td> <?php echo ($_SESSION["price"] * $_SESSION["ticketsSold"]) ?> </td>
            </tr>

        </table>
    <br><br>
    <a href="DUMSSMainPage.php"> DUMSS Home Page </a>
</body>
</html>

