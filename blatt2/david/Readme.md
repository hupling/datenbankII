2.-3.)

Variante 1.
```sql
CREATE TYPE KontoT AS OBJECT (nummer INTEGER, stand FLOAT, art VARCHAR2(1)) ;
CREATE TYPE KontolisteT AS TABLE OF REF KontoT ;
CREATE TYPE ZweigstelleT AS OBJECT (name VARCHAR2(100), adresse VARCHAR2(100), leiter VARCHAR2(30), konto KontolisteT) ;
CREATE TYPE KundeT AS OBJECT(nummer INT, name VARCHAR2(100), adresse VARCHAR2(100), status VARCHAR2(30), konto KontolisteT) ;

CREATE TABLE Konto OF KontoT;
CREATE TABLE Zweigstelle OF ZweigstelleT NESTED TABLE Konto STORE AS Konto_nt ;
CREATE TABLE Kunde OF KundeT NESTED TABLE Konto STORE AS Konto_nt1 ;

INSERT INTO Konto KontoT values ('120768', '234,56', 's');
INSERT INTO Konto KontoT values ('678453', '-456,78', 'g');
INSERT INTO Konto KontoT values ('348973', '12567,56', 'g');
INSERT INTO Konto KontoT values ('987654', '789,65', 'g');
INSERT INTO Konto KontoT values ('745363', '-23,67', 's');

INSERT INTO Zweigstelle ZweigstelleT VALUES ('Bachdorf', 'Hochstr. 1', 1768, KontolisteT( (SELECT Ref(w) FROM Konto w WHERE w.nummer = 120768 ),(SELECT Ref(w) FROM Konto w WHERE w.nummer = 678453 ),(SELECT Ref(w) FROM Konto w WHERE w.nummer = 348973 ))) ;
INSERT INTO Zweigstelle ZweigstelleT VALUES ('Riedering', 'Simseestr. 3', 9823, KontolisteT( (SELECT Ref(w) FROM Konto w WHERE w.nummer = 987654 ),(SELECT Ref(w) FROM Konto w WHERE w.nummer = 745363 ))) ;

INSERT INTO Kunde KundeT VALUES (2345, 'H. Fach','Münchnerstr. 33','Geschäftskunde', KontolisteT ( (select Ref(w) from Konto w where w.nummer = 120768 ),(select Ref(w) from Konto w where w.nummer = 348973 ))) ;
INSERT INTO Kunde KundeT VALUES (7654, 'B. Meier','Eschenweg 12','Privatkunde', KontolisteT ( (select Ref(w) from Konto w where w.nummer = 987654 ))) ;
INSERT INTO Kunde KundeT VALUES (8764, 'J. Wiesner','Schellingstr. 52','Geschäftskunde', KontolisteT ( (select Ref(w) from Konto w where w.nummer = 745363 ),(select Ref(w) from Konto w where w.nummer = 678453 ),(select Ref(w) from Konto w where w.nummer = 348973 ))) ;
```

Variante 2.
```sql
CREATE TYPE Zweigstelle_T AS OBJECT (name VARCHAR2(100), adresse VARCHAR2(100), leiter VARCHAR2(30)) ;
CREATE TYPE Kunde_T AS OBJECT(nummer INT, name VARCHAR2(100), adresse VARCHAR2(100), status VARCHAR2(30)) ;
CREATE TYPE Kundenliste_T AS TABLE OF REF Kunde_T ;
CREATE TYPE Konto_T AS OBJECT (nummer INTEGER, stand FLOAT, art VARCHAR2(1), zweigstelle Ref Zweigstelle_T, Kunde Kundenliste_T) ;

CREATE TABLE Zweigstelle OF Zweigstelle_T ;
CREATE TABLE Kunde OF Kunde_T ;
CREATE TABLE Konto OF Konto_T NESTED TABLE Kunde STORE AS Kunde_nt ;

INSERT INTO Zweigstelle Zweigstelle_T VALUES ('Bachdorf', 'Hochstr. 1', 1768) ;
INSERT INTO Zweigstelle Zweigstelle_T VALUES ('Riedering', 'Simseestr. 3', 9823) ;

INSERT INTO Kunde KundeT VALUES (2345, 'H. Fach', 'Münchnerstr. 33', 'Geschäftskunde') ;
INSERT INTO Kunde KundeT VALUES (7654, 'B. Meier', 'Eschenweg 12', 'Privatkunde') ;
INSERT INTO Kunde KundeT VALUES (8764, 'J. Wiesner', 'Schellingstr. 52', 'Geschäftskunde') ;

INSERT INTO Konto Konto_T values ('120768', '234,56', 's', (SELECT Ref(z) FROM Zweigstelle z WHERE z.name = 'Bachdorf'), Kundenliste_T((SELECT Ref(k) FROM Kunde k WHERE k.nummer = 2345)));
INSERT INTO Konto Konto_T values ('678453', '-456,78', 'g', (SELECT Ref(z) FROM Zweigstelle z WHERE z.name = 'Bachdorf'), Kundenliste_T((SELECT Ref(k) FROM Kunde k WHERE k.nummer = 8764)));
INSERT INTO Konto Konto_T values ('348973', '12567,56', 'g', (SELECT Ref(z) FROM Zweigstelle z WHERE z.name = 'Bachdorf'), Kundenliste_T((SELECT Ref(k) FROM Kunde k WHERE k.nummer = 2345), (SELECT Ref(k) FROM Kunde k WHERE k.nummer = 8764)));
INSERT INTO Konto Konto_T values ('987654', '789,65', 'g', (SELECT Ref(z) FROM Zweigstelle z WHERE z.name = 'Riedering'), Kundenliste_T ((SELECT Ref(k) FROM Kunde k WHERE k.nummer = 7654)));
INSERT INTO Konto Konto_T values ('745363', '-23,67', 's', (SELECT Ref(z) FROM Zweigstelle z WHERE z.name = 'Riedering'), Kundenliste_T((SELECT Ref(k) FROM Kunde k WHERE k.nummer = 8764)));
```

4.a)

Variante 1.
```sql
SELECT deref(a.COLUMN_VALUE).nummer AS Kontonummer, deref(a.COLUMN_VALUE).stand AS Kontostand, deref(a.COLUMN_VALUE).art AS Kontoart,
z.adresse AS Zweigstellenadresse FROM Zweigstelle z, TABLE(z.konto) a ;
```

Variante 2.
```sql
SELECT k.nummer, k.stand, k.art, deref(k.zweigstelle).adresse AS Adresse FROM Konto k;
```

4.b)

Variante 1.
```sql
SELECT deref(a.COLUMN_VALUE).nummer AS Kontonummer, max(k.adresse) FROM Kunde k,
Table(k.konto) a GROUP BY deref(a.COLUMN_VALUE).nummer;
```
Variante 2.
```sql
SELECT k.nummer AS Kontonummer, deref(a.COLUMN_VALUE).adresse AS Kundenadresse FROM Konto k, Table(k.kunde) a;
```

5.) SQL Befehle in separater Datei
