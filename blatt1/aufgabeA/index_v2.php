<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Fußballverein-Abstimmung</title>
	</head>
	<body>
		<p>Wähle deinen lieblings Fußballverein:</p>
		<form action='index_v2.php' method='POST'>
			<fieldset>
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
					$stmt = $pdo->query("SELECT club FROM club");
					$clubs = $stmt->fetchAll();
					if(count($clubs) > 0) {
						foreach($clubs as $club) {
							echo '<input type="radio" id="'.$club['club'].'" name="club" value="'.$club['club'].'" />';
							echo '<label for="'.$club['club'].'">'.$club['club'].'</label> <br>';
						}
					}
				?>
				<input type="radio" id="newclub" value="other" name="club" />
				<label for="newclub">Anderer club:</label>
				<input id="newclub" name="clubname" maxlength="50">
				<br>
				<input type="submit" name="vote" value="Abstimmen" />
			</fieldset>
		</form>
		<?php
			if (isset($_POST['vote'])){
				try {
					if (empty($_POST['club']) || (empty($_POST['clubname']) && $_POST['club'] == "other")){
						echo "<h3>Bitte treffen sie eine Auswahl bevor sie auf den \"Abstimmen\" Button klicken!</h3>";
					} else {
						$club = $_POST['club'];
						if ($club=="other") {
							$club = $_POST['clubname'];
						}
						$stmt = $pdo->prepare("UPDATE club SET votes = votes + 1 WHERE club = ?");
						$stmt->bindValue(1, $club);
						$stmt->execute();
						
						if ($stmt->rowCount() == 0) {
							$stmt = $pdo->prepare("INSERT INTO club (club, votes) VALUES (?, 1)");
							$stmt->bindValue(1, $club);
							$stmt->execute();
						}
						echo "<h3>Sie haben für folgenden Fußballverein abgestimmt: ".$club."</h3>";
						$stmt = $pdo->query("SELECT SUM(votes) AS anz FROM club");
						$anzahl_user = $stmt->fetch();
						echo "Anzahl Teilnehmer: ".$anzahl_user['anz']."<br>";
						echo '<a href="./statistik_v2.php">Link zur Statistik</a>';
					}
				} catch (Exception $e) {
					die(var_dump($e));
				}
			}
		?>
	</body>
</html>