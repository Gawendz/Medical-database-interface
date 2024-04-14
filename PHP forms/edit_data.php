<?php
// Pobranie danych uwierzytelniających bazy danych z zmiennych środowiskowych
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

// Sprawdzenie czy wszystkie zmienne środowiskowe zostały zdefiniowane
if (!$host || !$dbname || !$user || !$password) {
    die("Brak wymaganych zmiennych środowiskowych.");
}

// Połączenie z bazą danych
$dsn = "pgsql:host=$host;dbname=$dbname";
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Sprawdzenie, czy dane zostały przesłane z formularza i czy istnieje ID pacjenta
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
        // Pobranie danych z formularza
        $id = $_POST['id']; // ID pacjenta
        $imie = $_POST['imie'];
        $nazwisko = $_POST['nazwisko'];
        $data_urodzenia = $_POST['data_urodzenia'];
        $adres = $_POST['adres'];
        $telefon = $_POST['telefon'];

        // Zapytanie SQL do aktualizacji danych pacjenta
        $query = "UPDATE pacjenci SET imie = :imie, nazwisko = :nazwisko, data_urodzenia = :data_urodzenia, adres = :adres, telefon = :telefon WHERE id_pacjenta = :id";

        // Przygotowanie i wykonanie zapytania z użyciem prepared statement
        $statement = $pdo->prepare($query);
        $statement->bindParam(':imie', $imie);
        $statement->bindParam(':nazwisko', $nazwisko);
        $statement->bindParam(':data_urodzenia', $data_urodzenia);
        $statement->bindParam(':adres', $adres);
        $statement->bindParam(':telefon', $telefon);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        // Przekierowanie użytkownika po zapisaniu zmian
        header("Location: index.html");
        exit();
    } else {
        // Jeśli dane nie zostały przesłane lub brak ID pacjenta, wyświetl odpowiedni komunikat
        echo "Brak danych do aktualizacji lub brak ID pacjenta.";
    }
} catch (PDOException $e) {
    // Obsługa błędu związanego z połączeniem lub wykonaniem zapytania SQL
    die("Connection failed: " . $e->getMessage());
}
?>

