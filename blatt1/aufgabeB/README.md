# Teil B

1. Die Webseite ist das `index1.php` Dokument. Dort befinden sich die Funktionen aus der Teilaufgabe 1.
2. folgende Tabellen sind in der Datenbank `Praktikum` vorhanden:

Hier werden die Namen, Email-Adressen, Status (Mitglied oder kein Mitglied) gespeichert
```sql
CREATE TABLE mail_name (
    name varchar(50),
    email varchar(50),
    mitglied varchar(50),
    PRIMARY KEY (email));
```
Hier sind die Ligas gespeichert für das Formular
```sql
   CREATE TABLE liga(
    liga varchar(50),
    PRIMARY KEY (liga));
```
Hier werden die Emailadressen zu den einzelnen Ligas gespeichert.
```sql
    CREATE TABLE mail (
        email varchar(50),
        mailliste varchar(50),
        FOREIGN KEY (email) REFERENCES mail_name(email),
        FOREIGN KEY (mailliste) REFERENCES liga(liga)
    )
```
## `index1.php`

#### Anzeige der Ligas für die Checkboxen

```sql
SELECT * from liga

```
#### Eintrag des Namens,Email, Mitgliedstatus

```sql
INSERT INTO mail_name (name, email, mitglied) VALUES (?,?,?)
```
#### Eintragen der einzelnen Ligas mit Emailadressen in einer For-Schleife

```sql
INSERT INTO mail (email, mailliste) VALUES (?,?)
```





3. Die Datei `mail.php` enthält die Funktionen aus Aufgabe 7

## `mail.php`

#### Anzeigen der Ligas im Formular
```sql
SELECT * from liga
```
### Lange Abfrage zum Auswählen der Email-Adressen
* Auswählen des Mitgliderstatus aus der Mail_Name Tabelle

```sql
SELECT email from mail_name WHERE mitglied='" . $mitglied . "' AND 
```
* Join mit der Mail-Tabelle

```sql
EXISTS (SELECT DISTINCT email FROM mail WHERE mail.email = mail_name.email and (
```
* In einer Forschleife die einzelnen ausgewählten Ligas
```sql
 mailliste ='" . $liga . "' OR
```
* Zum Schluss noch die Klammer Schließen

```sql
 false))
```
