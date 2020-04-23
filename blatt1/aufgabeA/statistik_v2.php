<?php
    // DB connection info
	$host = "localhost";
	$user = "root";
	$pwd = "";
	$db = "praktikum";
	// Connect to database.
	try {
		$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pwd);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        die(var_dump($e));
    }
 $sql_select = "SELECT club, votes, votes/(SELECT SUM(votes) FROM club)*100 AS percent FROM club";
 $stmt = $pdo->query($sql_select);
 $registrants = $stmt->fetchAll();
 if(count($registrants) > 0) {
 	echo "<h2>Statistik:</h2>";
 	echo "<table>";
 	echo "<tr><th>Verein</th>";
 	echo "<th>Stimmenanzahl</th>";
    echo "<th>Prozentual</th></tr>";
	$sumvotes = 0;
 	foreach($registrants as $registrant) {
		$sumvotes += $registrant['votes'];
 		echo "<tr><td>".$registrant['club']."</td>";
 		echo "<td>".$registrant['votes']."</td>";
 		echo "<td>".$registrant['percent']." %</td></tr>";
     }
  	echo "</table><br>";
	echo "Gesamtanzahl Stimmen: $sumvotes";
 } else {
 	echo "Keine Einträge</h3>";
 }
 ?>