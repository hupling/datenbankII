<?php
    // DB connection info
    $host = "localhost";
    $user = "root";
    $pwd = "";
    $db = "praktikum";
    // Connect to database.
    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pwd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        die(var_dump($e));
    }
 $sql_select = "SELECT verein,COUNT(verein) as count, COUNT(verein)/(select count(*) from verein)*100 as percent FROM verein GROUP BY verein";
 $stmt = $conn->query($sql_select);
 $registrants = $stmt->fetchAll(); 
 if(count($registrants) > 0) {
 	echo "<h2>Statistik:</h2>";
 	echo "<table>";
 	echo "<tr><th>Verein</th>";
 	echo "<th>Stimmenanzahl</th>";
    echo "<th>Prozentual</th></tr>";
	$sumvotes = 0;
 	foreach($registrants as $registrant) {
		$sumvotes += $registrant['count'];
 		echo "<tr><td>".$registrant['verein']."</td>";
 		echo "<td>".$registrant['count']."</td>";
 		echo "<td>".$registrant['percent']." %</td></tr>";
     }
  	echo "</table><br>";
	echo "Gesamtanzahl Stimmen: $sumvotes";
 } else {
 	echo "Keine Einträge</h3>";
 }
 ?>