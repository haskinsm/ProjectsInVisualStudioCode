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
    <title>DUMMS Member Table</title>
</head>
<body>
    <img src="dumms.png" alt="DUMMS" height="136" width = "193"> <h2>Member Table</h2> 

    <table>
        <tr>
            <th>Student Number</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Full-time Student</th>
            <th>Gender</th>
            <th>School/Faculty</th>
        </tr>
        <?php

            include ("detail.php"); 

            $queryGetMemberTable  = "SELECT * FROM member";

            $resultQuery = $db->query($queryGetMemberTable);
            if ($resultQuery->num_rows > 0){
                ## Used to display all rows of data from members table
                while ( $row = $resultQuery -> fetch_assoc()){
                    echo '<tr>';
                
                    echo '<td>'.$row["dbstud_num"].'</td>';
                    echo '<td>'.$row["dbfull_name"].'</td>';
                    echo '<td>'.$row["dbemail"].'</td>';
                    echo '<td>'.$row["dbfulltime_stud"].'</td>';
                    echo '<td>'.$row["dbgender"].'</td>';
                    echo '<td>'.$row["dbschool"].'</td>';

                    echo '</tr>';
                }
                
            }

        ?>
    </table>

    <br><br><br><br><br>
    <a href="DUMSSMainPage.php"> DUMSS Home Page </a>
</body>
</html>

