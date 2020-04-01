<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Newsletter</title>
</head>

<body>
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
    ?>
    <form method="post" action="./mail.php" enctype="multipart/form-data">
        Mailing Liste für den Fußball
        <br>
        <br>
        <input type="radio" id="c" name="mitglied" value="alle" checked />
        <label for="c"> Alle</label> <br>

        <input type="radio" id="a" name="mitglied" value="ja" />
        <label for="a"> Fußballverein-Mitglieder</label> <br>
        <input type="radio" id="b" name="mitglied" value="nein" />
        <label for="b">Nicht-Mitglieder</label> <br>


        Mailliste <br>

        <?php
        $sql_select = "SELECT * from liga";
        $stmt = $conn->query($sql_select);
        $ligas = $stmt->fetchAll();
        foreach ($ligas as  $liga) {
            echo "<input type='checkbox' id='liga' name='liga[]' value='" . $liga['liga'] . "'>";
            echo "<label for='liga'>" . $liga['liga'] . "</label><br>";
        }

        ?>


        <label for="Betreff ">Betreff:
            <input type="text" id="name" name="Betreff" required>

        </label><br>
        <label for="text">Inhalt:
            <textarea id="text" name="text" required></textarea>
        </label>
        <br>


        <input type="submit" name="submit" value="Versenden" />

    </form>

    <?php

    if (!empty($_POST)) {
        $mitglied = $_POST['mitglied'];
        $sql_query = "";
        if ($mitglied == "alle") {
            $sql_query .= "SELECT email from mail_name where";
        } else {
            $sql_query .= "SELECT email from mail_name WHERE mitglied='" . $mitglied . "' AND ";
        }
        echo "<br>";

        if (empty($_POST['liga'])) {

            //fans ohne mail verteiler
            $sql_query .= " NOT EXISTS (SELECT DISTINCT email FROM mail WHERE mail.email = mail_name.email)";
        } else {

            //alle         
            $liga_checkbox = $_POST['liga'];

            $sql_query .= " EXISTS (SELECT DISTINCT email FROM mail WHERE mail.email = mail_name.email and (";
            foreach ($liga_checkbox as  $liga) {
                $sql_query .= " mailliste ='" . $liga . "' OR";
            }
            $sql_query .= " false))";
        }
        //echo $sql_query;

        $stmt = $conn->query($sql_query);
        $email_adressen = $stmt->fetchAll();


        if (count($email_adressen) > 0) {
            echo "<h2>Email-Adressen:</h2>";
            echo "<table>";
            echo "<tr><th>Email</th>";

            echo "</tr>";
            foreach ($email_adressen as $email_adresse) {
                echo "<tr><td>" . $email_adresse['email'] . "</td>";
                echo "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "Keine Einträge</h3>";
        }
    }
    ?>

</body>

</html>