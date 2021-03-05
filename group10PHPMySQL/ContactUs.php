<!-- 
    Purpose of Script: Contact us page
    Written by: Michael H
    last updated: Michael 12/02/21, Michael 19/02/21, Michael 05/03/21
        Added malito link to my own email address, changed link to fictional DPH email (As Im sure Aideen would not want her email linked), added accounts payable malito link
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact us</title>
    <link rel="stylesheet" href="WebsiteStyle.css"> <!-- All CSS should be added to the WebsiteStyle.css file and then it will be imported here. If want a unique 
            style for something should be done in line like so: E.G:   <h1 style="color:blue;text-align:center;">  This is a heading </h1>       -->
</head>
<body>

    <?php include 'UniversalMenuBar.php';?> <!-- Imports code for menu bar from another php file-->

    <br>

    <h1>
        General Queries:
    </h1>

    <h2>
        If you have any general queries please 
        <a href="mailto:DublinPartyHireQueries@gmail.com?body= Hello, This is in relation to ..." style='color: white;'>Email us! </a>
    </h2>

    <h1>
        Payment or Order Queries
    </h1>

    <h2> 
        Please note orders must be paid for before the delivery/pick-up date. Please contact Accounts Payable, 01 756 1113, <a href="mailto:accounts@dph.ie?body=" style="color: white;"> accounts@dph.ie </a> 
    </h2>


</body>
</html>