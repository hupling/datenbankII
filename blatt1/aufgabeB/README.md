# Teil B

1. Die Webseite ist das `index1.php` Dokument. Dort befinden sich die Funktionen aus der Teilaufgabe 1.
2. folgende Tabellen sind in der Datenbank `Praktikum` vorhanden:

```sql
CREATE TABLE mail_name (
    name varchar(50),
    email varchar(50),
    mitglied varchar(50),
    PRIMARY KEY (email));

   CREATE TABLE liga(
    liga varchar(50),
    PRIMARY KEY (liga));

    CREATE TABLE mail (
        email varchar(50),
        mailliste varchar(50),
        FOREIGN KEY (email) REFERENCES mail_name(email),
        FOREIGN KEY (mailliste) REFERENCES liga(liga)
    )
```
3. Die Datei `mail.php` enthält die Funktionen aus Aufgabe 7


