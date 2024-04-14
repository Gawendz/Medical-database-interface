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

    // Ustawienie raportowania błędów
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Sprawdzenie, czy dane zostały przesłane z formularza i czy istnieje ID leku
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
        // Pobranie ID leku do usunięcia
        $id = $_POST['id'];

        // Zapytanie SQL do usunięcia leku z bazy danych
        $query = "DELETE FROM leki WHERE id_leku = :id";

        // Przygotowanie i wykonanie zapytania z użyciem prepared statement
        $statement = $pdo->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        // Zwrócenie odpowiedzi w formie JSON o sukcesie
        echo json_encode(array('success' => true));
    } else {
        // Jeśli dane nie zostały przesłane lub brak ID leku, zwróć odpowiedni komunikat błędu
        echo json_encode(array('success' => false, 'message' => 'Brak danych do usunięcia lub brak ID leku.'));
    }
} catch (PDOException $e) {
    // Obsługa błędu związanego z połączeniem lub wykonaniem zapytania SQL
    die("Connection failed: " . $e->getMessage());
}
?>

