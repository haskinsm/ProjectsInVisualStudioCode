<?php

$host = "localhost";

$database = "haskinsm_db";

$user = "haskinsm";

$password = "ziemae2Z";




@ $db = mysqli_connect($host, $user, $password, $database);

$db->select_db($database);


if (mysqli_connect_errno())
{
echo 'Error connecting to the db.';
exit;
}


?>


