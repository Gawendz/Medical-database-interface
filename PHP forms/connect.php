<?php
// Pobranie danych uwierzytelniających bazy danych z zmiennych środowiskowych
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

// Sprawdzenie czy wszystkie zmienne środowiskowe zostały zdefiniowane
if (!$host || !$port || !$dbname || !$user || !$password) {
    die("Brak wymaganych zmiennych środowiskowych.");
}

// Tworzenie DSN (Data Source Name) dla połączenia z bazą danych
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";

try {
    // Utworzenie połączenia z bazą danych przy użyciu PDO
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully to the database";
} catch (PDOException $e) {
    // Obsługa błędu w przypadku nieudanego połączenia
    die("Connection failed: " . $e->getMessage());
}
?>

