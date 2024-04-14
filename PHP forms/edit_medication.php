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

    // Sprawdzenie, czy dane zostały przesłane z formularza i czy istnieje ID leku
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
        // Pobranie danych z formularza
        $id = $_POST['id'];
        $id_pacjenta = $_POST['id_pacjenta'];
        $nazwa_leku = $_POST['nazwa_leku'];
        $dawkowanie = $_POST['dawkowanie'];

        // Zapytanie SQL do aktualizacji danych leku
        $query = "UPDATE leki SET id_pacjenta = :id_pacjenta, nazwa_leku = :nazwa_leku, dawkowanie = :dawkowanie WHERE id_leku = :id";

        // Przygotowanie i wykonanie zapytania z użyciem prepared statement
        $statement = $pdo->prepare($query);
        $statement->bindParam(':id_pacjenta', $id_pacjenta, PDO::PARAM_INT);
        $statement->bindParam(':nazwa_leku', $nazwa_leku, PDO::PARAM_STR);
        $statement->bindParam(':dawkowanie', $dawkowanie, PDO::PARAM_STR);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        // Przekierowanie użytkownika po zapisaniu zmian
        header("Location: index.html");
        exit();
    } else {
        // Jeśli dane nie zostały przesłane lub brak ID leku, wyświetl odpowiedni komunikat
        echo "Brak danych do aktualizacji lub brak ID leku.";
    }
} catch (PDOException $e) {
    // Obsługa błędu związanego z połączeniem lub wykonaniem zapytania SQL
    die("Connection failed: " . $e->getMessage());
}
?>

