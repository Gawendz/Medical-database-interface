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

    // Sprawdzenie, czy dane zostały przesłane z formularza i czy istnieje ID wyniku lab
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
        // Pobranie ID wyniku lab
        $id = $_POST['id'];

        // Zapytanie SQL do usunięcia rekordu z tabeli wyniki_lab
        $query = "DELETE FROM wyniki_lab WHERE id_wyniku = :id";

        // Przygotowanie i wykonanie zapytania z użyciem prepared statement
        $statement = $pdo->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        // Utworzenie tablicy JSON z odpowiedzią sukcesu
        $response = array("success" => true);

        // Wyślij odpowiedź w formacie JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Jeśli dane nie zostały przesłane lub brak ID wyniku lab, wyślij odpowiedź błędu
        $response = array("success" => false, "message" => "Brak danych do usunięcia lub brak ID wyniku lab.");

        // Wyślij odpowiedź w formacie JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} catch (PDOException $e) {
    // Obsługa błędu związanego z połączeniem lub wykonaniem zapytania SQL
    die("Connection failed: " . $e->getMessage());
}
?>

