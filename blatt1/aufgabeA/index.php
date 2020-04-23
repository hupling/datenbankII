<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fußballverein-Abstimmung</title>
</head>
<body>
    <form method="post" action="index.php" enctype="multipart/form-data">
        <p>Geben Sie Ihren Fußballverein an:</p>
        <fieldset>
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
				$stmt = $conn->query("SELECT verein FROM verein GROUP BY verein");
				$vereine = $stmt->fetchAll();
				if(count($vereine) > 0) {
					foreach($vereine as $verein) {
						echo '<input type="radio" id="'.$verein['verein'].'" name="Verein" value="'.$verein['verein'].'" />';
						echo '<label for="'.$verein['verein'].'">'.$verein['verein'].'</label> <br>';
					}
				}
			?>
			
            <input type="radio" id="h" name="Verein" value="sonstiges" />
            <input type="text" id="h" name="sonstiges" maxlength="50" > <br>
			<br>
            <input type="submit" name="submit" value="Abstimmen" />
        </fieldset>
    </form>
    <?php
    if (!empty($_POST)) {
        try {
            if (empty($_POST['Verein']) || (empty($_POST['sonstiges']) && $_POST['Verein'] == "sonstiges")) {
                $message = "Bitte Auswahl treffen";
                echo "<script type='text/javascript'>alert('$message');</script>";
            } else {
                $verein = $_POST['Verein'];
                $sonst = $_POST['sonstiges'];
                if ($verein == "sonstiges") {
                    $verein = trim($sonst);
                }
                // Insert data
                $sql_insert = "INSERT INTO verein (verein) VALUES (?)";
                $stmt = $conn->prepare($sql_insert);
                $stmt->bindValue(1, $verein);
                $stmt->execute();
				
				echo "<h3>Eintrag gespeichert: $verein</h3>";
				$statement = $conn->query("SELECT COUNT(*) AS anz FROM verein");
				$anzahl_user = $statement->fetch();
				echo "Anzahl der bisherigen Teilnehmer: ".$anzahl_user['anz']."<br>";
				echo '<a href="./statistik.php">Link zur Statistik</a>';
            }
        } catch (Exception $e) {
            die(var_dump($e));
        }
    }
    ?>
</body>
</html>