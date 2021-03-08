    <?php 
    //Purpose of Script: create csv
    //Written by: Jason Yang
    //last updated: 6/3/21, 7/3/21
    
    // Start the session
    session_start();
    include ("ServerDetail.php");

    $id = $_SESSION["id"];

    if(isset($_POST["export"]))  
    {  
         header('Content-Type: text/csv; charset=utf-8');  
         header('Content-Disposition: attachment; filename=data.csv');  
         $output = fopen("php://output", "w");  
         fputcsv($output, array('Employee ID','Employee Name','Date','Hours Worked'));  
         $query = "SELECT * FROM Employees, Shifts WHERE Employees.Worker_ID = $id AND Shifts.Worker_ID = $id";  
         $result = mysqli_query($link, $query);  
         while($row = mysqli_fetch_assoc($result))  
         {  
            $name = $row['Worker_Name'];
                
            $datestep1 = $row["Date_Of_Entry"];
            $datestep2  = strtotime($datestep1);
            $datefinal = date("d M Y", $datestep2); 
                
            $timestep1 = $row["Clock_In_Time"];
            $timestep2 = $row["Clock_Out_Time"];
            $timestep3  = strtotime($timestep1);
            $timestep4  = strtotime($timestep2);
            $timefinal = date("H", $timestep4 - $timestep3); 

            fputcsv($output, array($id, $name, $datefinal, $timefinal));  
         }  
         fclose($output);  
    } 
    ?>
