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
        <label for="a"> Mitglieder im Fußballverein?</label> <br>
        <input type="radio" id="b" name="mitglied" value="nein" />
        <label for="b"> Nicht-Mitglieder</label> <br>


        Mailliste <br>

        <?php
        $sql_select = "SELECT * from liga";
        $stmt = $conn->query($sql_select);
        $registrants = $stmt->fetchAll();
        foreach ($registrants as $key => $registrant) {
            echo "<input type='checkbox' id='liga' name='liga[]' value='" . $registrant['liga'] . "'>";
            echo "<label for='liga'>" . $registrant['liga'] . "</label><br>";
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
        $hallo = "";
        if ($mitglied == "alle") {
            $hallo .= "SELECT email from mail_name where";
        } else {
            $hallo .= "SELECT email from mail_name WHERE mitglied='" . $mitglied . "' AND ";
        }
        echo "<br>";
       
        if (empty($_POST['liga'])) {
             
        //fans ohne mail verteiler
            $hallo .= " NOT EXISTS (SELECT DISTINCT email FROM mail WHERE mail.email = mail_name.email)";
        } else {

            //alle         
            $liga = $_POST['liga'];

            $hallo .= " EXISTS (SELECT DISTINCT email FROM mail WHERE mail.email = mail_name.email and (";
            foreach ($liga as  $re) {
                $hallo .= " mailliste ='" . $re . "' OR";
            }
            $hallo .= " false))";
        }
        echo $hallo;

        $sql_select = $hallo;
        $stmt = $conn->query($sql_select);
        $registrants = $stmt->fetchAll();


        if(count($registrants) > 0) {
            echo "<h2>Statistik:</h2>";
            echo "<table>";
            echo "<tr><th>Email</th>";

            echo "</tr>";
            foreach($registrants as $registrant) {
                echo "<tr><td>".$registrant['email']."</td>";
              echo"</td></tr>";
            }
             echo "</table>";
        } else {
            echo "Keine Einträge</h3>";
        }
      
    }
    ?>

</body>

</html>