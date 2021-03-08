<!-- 
    Purpose of Script: Add new Items Success Message
    Written by: Jason Yang
    last updated: Jason 1/3/21
-->

<?php
    // Start the session
    session_start();

    $_SESSION = array(); 
    session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Add Items Success </title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
</head>
<body>

    <?php include 'UniversalMenuBar.php';
    echo '<br>';
    include 'ManagerMenuBar.php';?> <!-- Imports code for manager menu bar from another php file-->

        <h2>Item added successfully!</h2>
    </body> 
</html>