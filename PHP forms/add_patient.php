<?php
// Pobranie danych uwierzytelniających bazy danych z zmiennych środowiskowych
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

// Sprawdzenie, czy otrzymano dane z formularza
if (isset($_POST['imie'], $_POST['nazwisko'], $_POST['data_urodzenia'], $_POST['adres'], $_POST['telefon'])) {
    // Pobranie danych z formularza
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $data_urodzenia = $_POST['data_urodzenia'];
    $adres = $_POST['adres'];
    $telefon = $_POST['telefon'];

    try {
        // Połączenie z bazą danych
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Zapytanie SQL dodające nowego pacjenta do bazy danych
        $query = "INSERT INTO Pacjenci (imie, nazwisko, data_urodzenia, adres, telefon) VALUES (:imie, :nazwisko, :data_urodzenia, :adres, :telefon)";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':imie', $imie, PDO::PARAM_STR);
        $statement->bindParam(':nazwisko', $nazwisko, PDO::PARAM_STR);
        $statement->bindParam(':data_urodzenia', $data_urodzenia, PDO::PARAM_STR);
        $statement->bindParam(':adres', $adres, PDO::PARAM_STR);
        $statement->bindParam(':telefon', $telefon, PDO::PARAM_STR);
        $statement->execute();

        // Przekierowanie użytkownika z powrotem do strony głównej
        header("Location: index.html");
        exit(); // Zapobiega dalszemu wykonywaniu kodu po przekierowaniu
    } catch (PDOException $e) {
        // Obsłuż błąd związany z wykonaniem zapytania SQL
        echo "Błąd wykonania zapytania SQL: " . $e->getMessage();
    }
}
?>

