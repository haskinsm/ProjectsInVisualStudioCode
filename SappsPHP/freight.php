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
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cheese Freight</title>
</head>
<body>
    <img src="Cheese.jpg" alt="cheese" height="136" width = "193"> <h2>Lloyd Cheese Company</h2>
    <h4>Freight Costs</h4>
        <table>
            <tr>
                <th>Distance</th>
                <tr>Cost</tr>
            </tr>
            <?php 
                /*
                $distance = 50;
                while($distance <= 250){
                    echo "<tr>\n <td>$distance</td>\n";
                    echo "<td>".$distance/10 . "</td>\n</tr>\n";
                    $distance += 50;
                } */
                for($distance = 50; $distance <= 250; $distance += 50){
                    echo "<tr>\n <td>$distance</td>\n";
                    echo "<td>".$distance/10 . "</td>\n</tr>\n";
                }
            ?>
        </table>
</body>
</html>