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
        // Pobranie danych z formularza
        $id = $_POST['id']; // ID planu leczenia
        $id_pacjenta = $_POST['id_pacjenta'];
        $id_diagnozy = $_POST['id_diagnozy'];
        $zalecane_leczenie = $_POST['zalecane_leczenie'];
        $data_rozpoczecia = $_POST['data_rozpoczecia'];

        // Zapytanie SQL do aktualizacji danych planu leczenia
        $query = "UPDATE plany_leczenia SET id_pacjenta = :id_pacjenta, id_diagnozy = :id_diagnozy, zalecane_leczenie = :zalecane_leczenie, data_rozpoczecia = :data_rozpoczecia WHERE id_planu = :id";

        // Przygotowanie i wykonanie zapytania z użyciem prepared statement
        $statement = $pdo->prepare($query);
        $statement->bindParam(':id_pacjenta', $id_pacjenta, PDO::PARAM_INT);
        $statement->bindParam(':id_diagnozy', $id_diagnozy, PDO::PARAM_INT);
        $statement->bindParam(':zalecane_leczenie', $zalecane_leczenie, PDO::PARAM_STR);
        $statement->bindParam(':data_rozpoczecia', $data_rozpoczecia, PDO::PARAM_STR);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        // Przekierowanie użytkownika po zapisaniu zmian
        header("Location: index.html");
        exit();
    } else {
        // Jeśli dane nie zostały przesłane lub brak ID planu leczenia, wyświetl odpowiedni komunikat
        echo "Brak danych do aktualizacji lub brak ID planu leczenia.";
    }
} catch (PDOException $e) {
    // Obsługa błędu związanego z połączeniem lub wykonaniem zapytania SQL
    die("Connection failed: " . $e->getMessage());
}
?>

