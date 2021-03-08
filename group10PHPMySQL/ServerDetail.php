
<?php

$host = "localhost";

$database = "stu33001_2021_group_10_db";

$user = "group_10";

$password = "Ugh3Aiko";


@ $link = mysqli_connect($host, $user, $password, $database);

$link->select_db($database);


if (mysqli_connect_errno())
{
echo 'Error connecting to the db.';
exit;
}


?>
