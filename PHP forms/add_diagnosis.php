<?php
// Sprawdź, czy żądanie zostało wykonane za pomocą metody POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Uzyskaj dane z formularza
    $pacjentId = $_POST['pacjentId'];
    $stanZdrowia = $_POST['stanZdrowia'];
    $data = $_POST['data'];

    // Pobierz dane uwierzytelniające bazy danych z zmiennych środowiskowych
    $host = getenv('DB_HOST');
    $dbname = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $password = getenv('DB_PASSWORD');

    // Połącz z bazą danych
    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Przygotuj zapytanie SQL do dodania diagnozy
        $query = "INSERT INTO Diagnozy (id_pacjenta, stan_zdrowia, data) VALUES (:pacjentId, :stanZdrowia, :data)";

        // Wykonaj zapytanie SQL z użyciem prepared statements
        $statement = $pdo->prepare($query);
        $statement->bindParam(':pacjentId', $pacjentId, PDO::PARAM_INT);
        $statement->bindParam(':stanZdrowia', $stanZdrowia, PDO::PARAM_STR);
        $statement->bindParam(':data', $data, PDO::PARAM_STR);

        if ($statement->execute()) {
            // Jeśli diagnoza została dodana pomyślnie, zwróć sukces
            http_response_code(200);
            echo json_encode(array("message" => "Diagnoza została dodana."));
        } else {
            // Jeśli wystąpił błąd podczas dodawania diagnozy, zwróć błąd
            http_response_code(500);
            echo json_encode(array("message" => "Nie udało się dodać diagnozy."));
        }
    } catch (PDOException $e) {
        // Obsłuż błąd związany z wykonaniem zapytania SQL
        http_response_code(500);
        echo json_encode(array("message" => "Błąd wykonania zapytania SQL: " . $e->getMessage()));
    }
} else {
    // Jeśli żądanie nie było metodą POST, zwróć błąd
    http_response_code(405);
    echo json_encode(array("message" => "Metoda żądania nie jest obsługiwana."));
}
?>

