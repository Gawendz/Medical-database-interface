<?php
// Ustawienie raportowania błędów
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

    // Zapytanie SQL do pobrania danych z widoku
    $query = "SELECT * FROM Informacje_o_pacjentach";

    // Przygotowanie i wykonanie zapytania
    $statement = $pdo->query($query);
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Ustawienie nagłówka Content-Type na application/json
    header('Content-Type: application/json');

    // Zwrócenie wyników jako JSON
    echo json_encode($result);
} catch (PDOException $e) {
    // Obsługa błędu związanego z połączeniem lub wykonaniem zapytania SQL
    die("Connection failed: " . $e->getMessage());
}
?>

