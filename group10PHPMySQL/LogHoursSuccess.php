<!-- 
    Purpose of Script: Show success message for hours logged
    Written by: Jason Yang
    last updated: Jason 16/02/21
    Could add session variable for more personalized response
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success!</title>
    <link rel="stylesheet" href="WebsiteStyle.css"> 
    <!-- All CSS should be added to the WebsiteStyle.css file and then it will be imported here. 
    If we want a unique style for something should be done in line like so: E.G:   
    <h1 style="color:blue;text-align:center;">  This is a heading </h1>       -->
</head>
<body>

    <?php include 'UniversalMenuBar.php';?> <!-- Imports code for menu bar from another php file-->

    <br>

    <?php include 'StaffMenuBar.php';?> <!-- Imports code for manager menu bar from another php file-->

    <h3>Hours logged successfully!</3>
    </body> 
</html>
