<!--
    Purpose of Script: This is the code for the logout button
    Written by: Harry O'Brien
    last updated: Harry 21/02/21
    Source for Login form: https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
-->

<?php
// Initialize the session
session_start();
 
// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();
 
// Redirect to login page
header("location: HomePage.php");
exit;
?>