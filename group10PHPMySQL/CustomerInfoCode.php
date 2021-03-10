<?php
// Initialize the session
session_start();
 
 

include "ServerDetail.php";
if(isset($_POST['addCustomerInfoBtn'])){

    global $link;

    $name     = $_POST['name']; 
    $address  = $_POST['address'];
    $course   = $_POST['course'];
    $email    = $_SESSION['Email'];
    $phone    = $_POST['phone'];
   
    
    $queryEmail         = "SELECT * FROM Customers WHERE Business_Email = '$email' LIMIT 1";
    $queryEmailResult   = mysqli_query($link , $queryEmail); 
    if(mysqli_num_rows($queryEmailResult) > 0){
        header("Location: AddCustomerInfo.php?emailExist");
        exit;
    }

    $query =   "INSERT INTO Customers set 
                Business_Name    	= '".str_replace("'", "\'", $name)."', 
                Eircode    		= '$course',
                Business_Email     	= '$email',
                Business_Phone 		=  $phone,
                Business_Address   	= '$address' ";
                 

    $Result = mysqli_query($link , $query);

    
    if(isset($_SESSION['id']) && $_SESSION['isOfficer'] == 1){
        header("Location: index.php?successJoining&name=$name");
    }
    else
    {
        header("Location: CustomerHomePage.php?successJoining&name=$name");
    }
    

}

if(isset($_POST['updateCustomerInfoBtn'])){

    global $link;

    $name     = $_POST['name']; 
    $address  = $_POST['address'];
    $course   = $_POST['course'];
    $email    = $_SESSION['Email'];
    $phone    = $_POST['phone'];
   
    $query =   "UPDATE Customers set 
                Business_Name    	= '".str_replace("'", "\'", $name)."', 
                Eircode    		= '$course',
                Business_Phone 		=  $phone,
                Business_Address   	= '$address' 
		where Business_Email     	= '$email'";
       

    $Result = mysqli_query($link , $query);

    
    if(isset($_SESSION['id']) && $_SESSION['isOfficer'] == 1){
        header("Location: index.php?successJoining&name=$name");
    }
    else
    {
        header("Location: CustomerHomePage.php?successJoining&name=$name");
    }
    

}

if(isset($_POST['addTicketBtn'])){

    global $conn;

    $event        = $_POST['event']; 
    $quantity     = $_POST['quantity'];

    session_start();
    if(isset($_SESSION['id']) && $_SESSION['isOfficer'] == 1){
        $organizer    = $_POST['organizer'];
    }
    else
    {
        $organizer    = $_SESSION['id'];
    }

    $querySum       = "SELECT sum(quantity) as total FROM ticket WHERE 
                             member_id = $organizer AND event_id = $event LIMIT 1";
    $querySumResult = mysqli_query($conn , $querySum); 
    if(mysqli_num_rows($querySumResult) > 0){
        $row = mysqli_fetch_assoc($querySumResult);
        if($row['total'] == 4){
            header("Location: ticket.php?ticketError");
            exit; 
        }
        
        $total = $row['total'] + $quantity;
        if($total > 4){
            header("Location: ticket.php?ticketError");
            exit;  
        } 

    }

    $query = "INSERT INTO ticket set 
                member_id = '$organizer', 
                event_id  = '$event',
                quantity  = $quantity "; 
    $Result = mysqli_query($conn , $query); 
    header("Location: ticket.php?success");

}

if(isset($_POST['addEventBtn'])){

    global $conn;

    $title        = $_POST['title']; 
    $price        = $_POST['price'];
    $organizer    = $_POST['organizer'];
    $start_time   = $_POST['start_time'];
    $end_time     = $_POST['end_time'];
    $event_date   = $_POST['event_date'];
    $location     = $_POST['location'];
    						
    

    $query = "INSERT INTO event set 
                title      = '$title', 
                price      = '$price',
                organizer_id  = $organizer,
                start_time = '$start_time',
                end_time   = '$end_time',
                location   = '$location',
                event_date = '$event_date' "; 
    $Result = mysqli_query($conn , $query); 
    header("Location: event.php?success");

}

if(isset($_POST['submitLogin'])){

    global $conn;

    $email = $_POST['email'];
    $pass  = md5($_POST['password']);

    $queryUser = "SELECT * FROM member WHERE email = '$email' AND password = '$pass' LIMIT 1";
    $result = mysqli_query($conn,$queryUser);
    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_array($result)){
            
            session_start();
            $_SESSION['id']        = $row['member_id'];
            $_SESSION['email']     = $row['email'];
            $_SESSION['dob']       = $row['dob'];
            $_SESSION['course']    = $row['course'];
            $_SESSION['email']     = $row['email'];
            $_SESSION['phone_num'] = $row['phone_num'];
            $_SESSION['address']   = $row['address']; 
            $_SESSION['isOfficer'] = $row['isOfficer']; 
            		  
        } // end of while loop

        header("Location: index.php");
    
    }
    else
    {
        header("Location: login.php?loginerror");
    }
    
    

}


?>