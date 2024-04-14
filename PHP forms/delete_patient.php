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
        // Pobranie ID pacjenta z formularza
        $id = $_POST['id'];

        // Zapytanie SQL do usunięcia pacjenta
        $query = "DELETE FROM pacjenci WHERE id_pacjenta = :id";

        // Przygotowanie i wykonanie zapytania z użyciem prepared statement
        $statement = $pdo->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        // Zwrócenie odpowiedzi w formacie JSON o sukcesie
        echo json_encode(array('success' => true));
    } else {
        // Zwrócenie odpowiedzi w formacie JSON w przypadku niepowodzenia
        echo json_encode(array('success' => false, 'message' => 'Nieprawidłowe dane wejściowe'));
    }
} catch (PDOException $e) {
    // Obsługa błędu związanego z połączeniem lub wykonaniem zapytania SQL
    die("Connection failed: " . $e->getMessage());
}
?>

