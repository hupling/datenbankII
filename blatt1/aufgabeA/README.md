# Teil A
### Version 1

1. Die Datei `index.php` beinhaltet die Webseite "Fußballverein-Abstimmung" zur Auswahl des bevorzugten Fußballvereins und das PHP Skript für die Zugriffe auf die Datenbank.
2. SQL-Statement zum Erstellen der benötigten Tabelle in der Datenbank `praktikum`:
```sql
CREATE TABLE verein (
  id int(11) NOT NULL AUTO_INCREMENT,
  verein varchar(50) NOT NULL,
  PRIMARY KEY (id)
);
```
3.  `statistik.php` enthält ein PHP Skript zur Auswertung der Abstimmung zum aktuellen Zeitpunkt und die Anzeige der Statistik.

### Version 2

1. `index_v2.php`: Version 2 mit kompakterer Speicherung in Datenbank.
2. SQL-Statement zum Erstellen der benötigten Tabelle in der Datenbank `praktikum`:
```sql
CREATE TABLE club (
  club varchar(50) NOT NULL,
  votes int(11) NOT NULL,
  PRIMARY KEY (club)
);
```
3. `statistik_v2.php` enthält ein PHP Skript zur Auswertung der Abstimmung zum aktuellen Zeitpunkt und die Anzeige der Statistik.

### Ablauf

1. Tabellen "verein" und/oder "club" in der Datenbank anlegen.
2. Eine der index pages auf dem localhost starten.
3. Bereits vorhanden Fußballverein auswählen oder einen neuen Verein in die Textbox eingeben.
4. Zum Absenden Button 'Abstimmen' klicken.
5. Gewählter Verein, Anzahl der Teilnehmer und ein Link zur Statistik Seite wird angezeigt.
6. Statistik Seite zeigt Auswertung der abgegebenen Stimmen pro Verein.
