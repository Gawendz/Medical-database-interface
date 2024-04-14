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

    // Sprawdzenie, czy dane zostały przesłane z formularza i czy istnieje ID planu leczenia
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
        // Pobranie ID planu leczenia z formularza
        $id = $_POST['id'];

        // Zapytanie SQL do usunięcia rekordu planu leczenia
        $query = "DELETE FROM plany_leczenia WHERE id_planu = :id";

        // Przygotowanie i wykonanie zapytania z użyciem prepared statement
        $statement = $pdo->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        // Zwrócenie odpowiedzi JSON informującej o powodzeniu operacji usuwania
        echo json_encode(array('success' => true));
    } else {
        // Jeśli dane nie zostały przesłane lub brak ID planu leczenia, zwróć błąd w formie JSON
        echo json_encode(array('success' => false, 'message' => 'Brak danych do usunięcia lub brak ID planu leczenia.'));
    }
} catch (PDOException $e) {
    // Obsługa błędu związanego z połączeniem lub wykonaniem zapytania SQL
    die("Connection failed: " . $e->getMessage());
}
?>

