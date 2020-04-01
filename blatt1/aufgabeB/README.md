# Teil B

2. Die Webseite ist das `index1.php` Dokument. Dort befinden sich die Funktionen aus der Teilaufgabe 1.
1. folgende Tabellen sind in der Datenbank `Praktikum` vorhanden:

```sql
CREATE TABLE mail_name (
    name varchar(20),
    email varchar(20),
    mitglied varchar(20),
    PRIMARY KEY (email));

   CREATE TABLE liga(
    liga varchar(20),
    PRIMARY KEY (liga));

    CREATE TABLE mail (
        email varchar(20),
        mailliste varchar(20),
        FOREIGN KEY (email) REFERENCES mail_name(email),
        FOREIGN KEY (liga) REFERENCES liga(liga)
    )
```
7. Die Datei `mail.php` enthält die Funktionen aus Aufgabe 7


