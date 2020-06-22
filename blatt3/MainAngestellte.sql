SET SERVEROUTPUT ON FORMAT WRAPPED;

DECLARE
    angestellter        angestellte_t;
    bcode	   NUMBER;
    gcode	   NUMBER;
    name_idx	   NUMBER;
    name1	   VARCHAR2(50);
    vorname	   VARCHAR2(50);
    ID		   NUMBER;
BEGIN

    FOR angestellter IN (
        SELECT
            name, (to_char(sysdate, 'YYYY') - to_char(geburtsdatum, 'YYYY')) AS jahre, berufsbezeichnung,
	    (monatsgehalt * 12) AS jahresgehalt, geschlecht, angestelltennr
        FROM
            angestellte
    ) LOOP
        name_idx := INSTR(angestellter.name,', ',1);
        if name_idx <> 0 then
            name1 := TRIM(SUBSTR(angestellter.name, 1, name_idx - 1));
            vorname := TRIM(SUBSTR(angestellter.name, name_idx + 2, LENGTH(angestellter.name)));
        else
            name_idx := INSTR(angestellter.name,' ',1);
            vorname := TRIM(SUBSTR(angestellter.name, 1, name_idx - 1));
            name1 := TRIM(SUBSTR(angestellter.name, name_idx + 1, LENGTH(angestellter.name)));
        end if;
	
        bcode := berufscode_bestimmen(angestellter.berufsbezeichnung);
	
        if angestellter.geschlecht = 'männlich' then
            gcode := 2;
        else if angestellter.geschlecht = 'weiblich' then
            gcode := 1;
        else
            gcode := 0;
        end if;
        end if;
        
        gcode := geschlecht_bestimmen(gcode, vorname);
	
        BEGIN
            SELECT personalnr
            INTO ID
            FROM zuordnungstab
            WHERE arbeiter_angestelltennr = angestellter.angestelltennr;
        EXCEPTION
            WHEN no_data_found THEN
                insert into zuordnungstab (system, arbeiter_angestelltennr) values
                (1, angestellter.angestelltennr);
            
                SELECT personalnr INTO ID FROM zuordnungstab
                WHERE arbeiter_angestelltennr = angestellter.angestelltennr;
            
                insert into personal values
                (ID, name1, vorname, angestellter.jahre, gcode, bcode, angestellter.jahresgehalt);
        END;
	    
        update personal
        set name = name1, vorname = vorname, "alter" = angestellter.jahre, geschlecht = gcode,
        berufscode = bcode, jahreseinkommen = angestellter.jahresgehalt
        where personalnr = ID;

        delete from angestellte where angestelltennr = angestellter.angestelltennr;
        
    END LOOP;
END;