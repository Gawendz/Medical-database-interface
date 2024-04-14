# Medical-database-interface
A database storing information about patients and their diagnoses, medications, test results and treatment plans. Hosting on Apache.

Entity relationship diagram:
![image (3)](https://github.com/Gawendz/Medical-database-interface/assets/105167719/c782243a-0418-4c1f-9572-033194940d67)


Relational diagram:
![diagram-export-28 03 2024-03_56_47](https://github.com/Gawendz/Medical-database-interface/assets/105167719/527a0cf4-001e-4385-bbeb-68407ec2f992)

**Table definitions:**

**CREATE TABLE Pacjenci** (
    id_pacjenta SERIAL PRIMARY KEY,
    imie VARCHAR(50),
    nazwisko VARCHAR(50),
    data_urodzenia DATE,
    adres VARCHAR(100),
    telefon VARCHAR(20)
    wiek int
);

**CREATE TABLE Diagnozy** (
    id_diagnozy SERIAL PRIMARY KEY,
    id_pacjenta INTEGER REFERENCES Pacjenci(id_pacjenta),
    stan_zdrowia VARCHAR(100),
    data DATE
);


**CREATE TABLE Leki** (
    id_leku SERIAL PRIMARY KEY,
    id_pacjenta INTEGER REFERENCES Pacjenci(id_pacjenta),
    nazwa_leku VARCHAR(50),
    dawkowanie VARCHAR(50)
);


**CREATE TABLE Wyniki_lab** (
    id_wyniku SERIAL PRIMARY KEY,
    id_pacjenta INTEGER REFERENCES Pacjenci(id_pacjenta),
    rodzaj_badania VARCHAR(50),
    wynik VARCHAR(50),
    data DATE
);


**CREATE TABLE Plany_leczenia** (
    id_planu SERIAL PRIMARY KEY,
    id_pacjenta INTEGER REFERENCES Pacjenci(id_pacjenta),
    id_diagnozy INTEGER REFERENCES Diagnozy(id_diagnozy),
    zalecane_leczenie VARCHAR(100),
    data_rozpoczecia DATE
);                         

**View definition: **

**CREATE VIEW Informacje_o_pacjentach AS SELECT**
    P.id_pacjenta,
    P.imie_nazwisko,
    P.data_urodzenia,
    D.stan_zdrowia,
    L.nazwa_leku,
    L.dawkowanie
FROM Pacjenci P
JOIN Diagnozy D ON P.id_pacjenta = D.id_pacjenta
JOIN Leki L ON P.id_pacjenta = L.id_pacjenta;

**Function definition:**

**CREATE OR REPLACE FUNCTION oblicz_wiek()**
RETURNS TRIGGER AS $$
BEGIN
    -- Oblicz wiek na podstawie daty urodzenia
    NEW.wiek := EXTRACT(YEAR FROM AGE(CURRENT_DATE, NEW.data_urodzenia));
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

**Trigger definition:**
CREATE TRIGGER trigger_oblicz_wiek
BEFORE INSERT OR UPDATE ON Pacjenci
FOR EACH ROW
EXECUTE FUNCTION oblicz_wiek();                

**Page appearance:**

![image](https://github.com/Gawendz/Medical-database-interface/assets/105167719/246a4744-fc51-4c54-86eb-6a810f86e787)







