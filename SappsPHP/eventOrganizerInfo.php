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
    
    <h2>Event Organizer Query <span class="tab"> <a href="DUMSSMainPage.php"> DUMSS Home Page </a> </span> </h2> 


    <h4>Please Find Event Organizer info below:</h4>
  
        <table>
            <tr>
                <td>Name of Organizer: </td>
                <td> <?php echo $_SESSION["full_name"] ?> </td>
            </tr>
            <tr>
                <td>Event Title: </td>
                <td> <?php echo  $_SESSION["title"]  ?> </td>
            </tr>
            <tr>
                <td>Event Date: </td>
                <td> <?php echo $_SESSION["date"]  ?> </td>
            </tr>
            <tr>
                <td>Contact Organizer through email: </td>
                <td> <a href="mailto:<?php echo $_SESSION["email"]; ?>?body=<?php echo "Hello ".$_SESSION["full_name"].", This is in relation to ".$_SESSION["title"]; ?>">
                        <?php echo $_SESSION["email"]; ?>
                    </a> 
                </td> 
            </tr>

        </table>
    <br><br>
    <a href="DUMSSMainPage.php"> DUMSS Home Page </a>
</body>
</html>

