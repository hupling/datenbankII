<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fußball</title>
</head>

<body>
    <form method="post" action="./index.php" enctype="multipart/form-data">
        <p>Geben Sie Ihren Fußballverein an:</p>
        <fieldset>
            <input type="radio" id="a" name="Verein" value="Bayern München" checked/>
            <label for="a"> Bayern München</label> <br>
            <input type="radio" id="b" name="Verein" value="FC Augsburg" />
            <label for="b"> FC Augsburg</label> <br>
            <input type="radio" id="c" name="Verein" value="Schalke 04" />
            <label for="c"> Schalke 04</label> <br>
            <input type="radio" id="d" name="Verein" value="Borussia Dortmund" />
            <label for="d"> Borussia Dortmund</label> <br>
            <input type="radio" id="e" name="Verein" value="Dynamo Dresden" />
            <label for="e"> Dynamo Dresden</label> <br>
            <input type="radio" id="f" name="Verein" value="RB Leipzig" />
            <label for="f"> RB Leipzig</label> <br>
            <input type="radio" id="g" name="Verein" value="TSV 1860 München" />
            <label for="g"> TSV 1860 München</label> <br>
            <input type="radio" id="h" name="Verein" value="sonstiges" />
            <input type="text" id="h" name="zutat"> <br>



            <input type="submit" name="submit" value="Abstimmen" />
        </fieldset>
    </form>
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


    if (!empty($_POST)) {
        try {

            if (empty($_POST['Verein']) || (empty($_POST['zutat']) && $_POST['Verein'] == "sonstiges")) {
                $message = "Bitte Auswahl treffen";
                echo "<script type='text/javascript'>alert('$message');</script>";
            } else {
                $time = date("Y-m-d H:i:s");
                $verein = $_POST['Verein'];
                $abc = $_POST['zutat'];
                if ($verein == "sonstiges") {
                    //echo "<script type='text/javascript'>alert('$message');</script>";
                    $verein = trim($abc);
                }
                // Insert data
                $sql_insert = "INSERT INTO verein (id, verein) VALUES (?,?)";
                $stmt = $conn->prepare($sql_insert);
                $stmt->bindValue(1, $time);
                $stmt->bindValue(2, $verein);

                $stmt->execute();
            }
        } catch (Exception $e) {
            die(var_dump($e));
        }


        $statement = $conn->prepare("SELECT * FROM verein");
        $statement->execute();
        $anzahl_user = $statement->rowCount();
        echo "<h3>Eintrag gespeichert!</h3>";
        echo "Es gitb $anzahl_user Teilnehmer.";
        echo '<a href="/statistik.php">link zur Statitik</a>';
    }

    ?>
</body>

</html>