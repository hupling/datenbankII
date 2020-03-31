<?php
    try {
        $conn = new PDO("mysql:host=localhost;dbname=clubvote", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        die(var_dump($e));
    }
 $sql_select = "SELECT club, votes, votes/(SELECT SUM(votes) FROM clubs)*100 AS percent FROM clubs";
 $stmt = $conn->query($sql_select);
 $registrants = $stmt->fetchAll(); 
 if(count($registrants) > 0) {
 	echo "<h2>Statistik:</h2>";
 	echo "<table>";
 	echo "<tr><th>Club</th>";
 	echo "<th>Votes</th>";
     echo "<th>Percentage</th></tr>";
 	foreach($registrants as $registrant) {
 		echo "<tr><td>".$registrant['club']."</td>";
 		echo "<td>".$registrant['votes']."</td>";
 		echo "<td>".$registrant['percent']." %</td></tr>";
     }
  	echo "</table>";
 } else {
 	echo "Keine Einträge</h3>";
 }
 ?>