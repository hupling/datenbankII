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
    <form method="post" action="./index1.php" enctype="multipart/form-data">
        <label for="name ">Name:
            <input type="text" id="name" name="name" required>

        </label><br>
        <label for="email">email:
            <input type="email" id="email" name="email" required>

        </label><br>

        Mitglied in Fußballverein? <br>
        <input type="radio" id="a" name="mitglied" value="ja" checked />
        <label for="a"> ja</label> <br>
        <input type="radio" id="b" name="mitglied" value="nein" />
        <label for="b"> nein</label> <br>

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



        <input type="submit" name="submit" value="Eintragen" />

    </form>
    <?php




    if (!empty($_POST)) {
        try {
            $sql_ausgewahlt = "SELECT * FROM mail_name WHERE email ='" .  $_POST['email'] . "'";
            $stmt1          = $conn->query($sql_ausgewahlt);
            $check          = $stmt1->fetchAll();
            if (count($check) > 0) {

                $message = "Email-Adresse schon vorhanden";
                echo "<script type='text/javascript'>alert('$message');</script>";
            } else {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $mitglied = $_POST['mitglied'];
                $liga = $_POST['liga'];
                // Insert data
                $sql_insert = "INSERT INTO mail_name (name, email, mitglied) VALUES (?,?,?)";
                $stmt = $conn->prepare($sql_insert);
                $stmt->bindValue(1, $name);
                $stmt->bindValue(2, $email);
                $stmt->bindValue(3, $mitglied);
                $stmt->execute();



                foreach ($liga as  $re) {
                    echo  $re . " <br>";

                    $sql_insert = "INSERT INTO mail (email, mailliste) VALUES (?,?)";
                    $stmt = $conn->prepare($sql_insert);
                    $stmt->bindValue(1, $email);
                    $stmt->bindValue(2, $re);
                    $stmt->execute();
                }
                echo "<h3>Eintrag gespeichert!</h3>";
            }
        } catch (Exception $e) {
            die(var_dump($e));
        }
    }

    ?>
</body>

</html>