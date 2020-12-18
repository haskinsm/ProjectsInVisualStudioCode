
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
        .tab { 
            display:inline-block; 
            margin-left: 40px; 
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DUMMS Ticket Table</title>
</head>
<body>
    <img src="dumms.png" alt="DUMMS" height="136" width = "193"> 
    
    <h2>Ticket Table <span class="tab"> <a href="DUMSSMainPage.php"> DUMSS Home Page </a> </span> </h2> 

    <table>
        <tr>
            <th>Ticket Number</th>
            <th>Student Number</th>
            <th>Event ID</th>
        </tr>
        <?php

            include ("detail.php"); 

            $queryGetTicketTable  = "SELECT * FROM ticket";

            $resultQuery = $db->query($queryGetTicketTable);
            if ($resultQuery->num_rows > 0){
                ## Used to display all rows of data from ticket table
                while ( $row = $resultQuery -> fetch_assoc()){
                    echo '<tr>';
                    
                    echo '<td>'.$row["dbticket_num"].'</td>';
                    echo '<td>'.$row["dbstudent_num"].'</td>';
                    echo '<td>'.$row["dbevent_id"].'</td>';

                    echo '</tr>';
                }
                
            }

        ?>
    </table>

    <br><br>
    <a href="DUMSSMainPage.php"> DUMSS Home Page </a>
</body>
</html>

