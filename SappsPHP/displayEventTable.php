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
        h3{
            background-color: pink;
            width: fit-content;
        }
        h4{
            background-color: aquamarine;
            width: fit-content;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DUMMS Event Table</title>
</head>
<body>
    <img src="dumms.png" alt="DUMMS" height="136" width = "193"> <h2>Event Table</h2> 

    <table>
        <tr> 
            <th>Event ID</th>
            <th>Organizer's Student Number</th>
            <th>Ticket Price</th>
            <th>Event Title</th>
            <th>Date</th>
            <th>Max Capacity</th>
            <th>Location</th>
            <th>Start Time</th>
            <th>Duration (in mins)</th>
        </tr>
        <?php

            include ("detail.php"); 
            ## Takes in all data from event table
            $queryGetEventTable  = "SELECT * FROM event";

            $resultEQuery = $db->query($queryGetEventTable);
            if ($resultEQuery->num_rows > 0){
                ## While loop used to display all data from event table, will exit loop when no more rows of data to display
                while ( $row = $resultEQuery -> fetch_assoc()){
                    echo '<tr>';
                    
                    echo '<td>'.$row["dbevent_id"].'</td>';
                    echo '<td>'.$row["dborganizer_id"].'</td>';
                    echo '<td>'.$row["dbprice"].'</td>';
                    echo '<td>'.$row["dbtitle"].'</td>';
                    echo '<td>'.$row["dbdate"].'</td>';
                    echo '<td>'.$row["dbcapacity"].'</td>';
                    echo '<td>'.$row["dblocation"].'</td>';
                    echo '<td>'.$row["dbstart_time"].'</td>';
                    echo '<td>'.$row["dbduration_mins"].'</td>';

                    echo '</tr>';
                }
                
            }

        ?>
    </table>

    <br><br><br><br><br>
    <a href="DUMSSMainPage.php"> DUMSS Home Page </a>
</body>
</html>

