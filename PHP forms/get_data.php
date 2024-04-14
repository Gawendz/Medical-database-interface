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

    // Zapytanie SQL pobierające dane z wszystkich tabel
    $query_pacjenci = "SELECT * FROM pacjenci";
    $query_diagnozy = "SELECT * FROM diagnozy";
    $query_leki = "SELECT * FROM leki";
    $query_wyniki_lab = "SELECT * FROM wyniki_lab";
    $query_plany_leczenia = "SELECT * FROM plany_leczenia";

    // Wykonanie zapytań SQL
    $statement_pacjenci = $pdo->query($query_pacjenci);
    $statement_diagnozy = $pdo->query($query_diagnozy);
    $statement_leki = $pdo->query($query_leki);
    $statement_wyniki_lab = $pdo->query($query_wyniki_lab);
    $statement_plany_leczenia = $pdo->query($query_plany_leczenia);

    // Pobranie wyników zapytań
    $pacjenci = $statement_pacjenci->fetchAll(PDO::FETCH_ASSOC);
    $diagnozy = $statement_diagnozy->fetchAll(PDO::FETCH_ASSOC);
    $leki = $statement_leki->fetchAll(PDO::FETCH_ASSOC);
    $wyniki_lab = $statement_wyniki_lab->fetchAll(PDO::FETCH_ASSOC);
    $plany_leczenia = $statement_plany_leczenia->fetchAll(PDO::FETCH_ASSOC);

    // Utworzenie obiektu zawierającego dane z wszystkich tabel
    $data = array(
        'pacjenci' => $pacjenci,
        'diagnozy' => $diagnozy,
        'leki' => $leki,
        'wyniki_lab' => $wyniki_lab,
        'plany_leczenia' => $plany_leczenia
    );

    // Ustawienie nagłówka Content-Type na application/json
    header('Content-Type: application/json');

    // Zwrócenie danych w formacie JSON
    echo json_encode($data);
} catch (PDOException $e) {
    // Obsługa błędu związanego z połączeniem lub wykonaniem zapytania SQL
    die("Connection failed: " . $e->getMessage());
}
?>

