<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Fußballverein Abstimmung</title>
	</head>
	<body>
		<p>Wähle deinen lieblings Fußballverein:</p>
		<form action='.' method='POST'>
			<div class="toggle-buttons">
				<?php
					try {
						$pdo = new PDO('mysql:host=localhost;dbname=clubvote', 'root', '');
						$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					} catch (Exception $e) {
						die(var_dump($e));
					}
					$select = $pdo->query("SELECT club FROM clubs");
					$clubs = $select->fetchAll();
					if(count($clubs) > 0) {
						foreach($clubs as $club) {
							echo '<input type="radio" id="'.$club['club'].'" name="club" value="'.$club['club'].'" />';
							echo '<label for="'.$club['club'].'">'.$club['club'].'</label> <br>';
						}
					}
				?>
				<input type="radio" id="newclub" value="other" name="club" />
				<label for="newclub">Anderer club:</label>
				<input id="newclub" name="clubname" maxlength="100">
			</div>
			<br>
			<button type="submit" name="vote">Abstimmen</button>
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
						$update = $pdo->prepare("UPDATE clubs SET votes = votes + 1 WHERE club = ?");
						$update->bindValue(1, $club);
						$update->execute();
						
						if ($update->rowCount() == 0) {
							$insert = $pdo->prepare("INSERT INTO clubs (club, votes) VALUES (?, 1)");
							$insert->bindValue(1, $club);
							$insert->execute();
						}
						echo "<h3>Sie haben für folgenden Fußballverein abgestimmt: ".$club."</h3>";
						$stmt = $pdo->query("SELECT SUM(votes) AS anz FROM clubs");
						$anzahl_user = $stmt->fetch();
						echo "Anzahl Teilnehmer: ".$anzahl_user['anz']."<br>";
						echo '<a href="statistik_v2.php">Link zur Statistik</a>';
					}
				} catch (Exception $e) {
					die(var_dump($e));
				}
			}
		?>
	</body>
</html>