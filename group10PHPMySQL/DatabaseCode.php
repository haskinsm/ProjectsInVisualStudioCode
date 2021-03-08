<?php

include "ServerDetail.php";



if(isset($_POST['addCustomerBtn'])){

    global $conn;

    $Email   	= $_POST['Email'];
    $Password = md5($_POST['Password']);

    $queryEmail         = "SELECT * FROM Passwords WHERE Email = '$Email' LIMIT 1";
    $queryEmailResult   = mysqli_query($conn , $queryEmail);
    if(mysqli_num_rows($queryEmailResult) > 0){
        header("Location: AddCustomer.php?emailExist");
        exit;
    }

    $query =   "INSERT INTO Passwords set
                Email      = '".str_replace("'", "\'", $Email)."',
                Password    		= '$Password',
                Position 		= 'Customer',";

  
    $Result = mysqli_query($conn , $query);

    session_start();
    header("Location: CustomerLogin.php?successJoining");



if(isset($_POST['submitLogin'])){

    global $conn;

    $Email = $_POST['Email'];
    $Password  = md5($_POST['Password']);

    $queryUser = "SELECT * FROM Passwords WHERE Email = '$Email' AND Password = '$Password' LIMIT 1";
    $result = mysqli_query($conn,$queryUser);
    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_array($result)){

            session_start();
            $_SESSION['Email']     = $row['Email'];

        }
        header("Location: CustomerHomePage.php");

    }
    else
    {
        header("Location: CustomerLogin.php?loginerror");
    }
}
?>
