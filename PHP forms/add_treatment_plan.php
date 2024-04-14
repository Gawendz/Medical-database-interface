<?php
// Sprawdzenie czy żądanie zostało wykonane za pomocą metody POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobranie danych z formularza
    $id_pacjenta = $_POST['id_pacjenta'];
    $id_diagnozy = $_POST['id_diagnozy'];
    $zalecane_leczenie = $_POST['zalecane_leczenie'];
    $data_rozpoczecia = $_POST['data_rozpoczecia'];

    try {
        // Pobranie danych uwierzytelniających bazy danych z zmiennych środowiskowych
        $host = getenv('DB_HOST');
        $dbname = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');

        // Połączenie z bazą danych
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Zapytanie SQL do dodania nowego planu leczenia
        $query = "INSERT INTO Plany_leczenia (id_pacjenta, id_diagnozy, zalecane_leczenie, data_rozpoczecia)
                  VALUES (:id_pacjenta, :id_diagnozy, :zalecane_leczenie, :data_rozpoczecia)";
        
        // Przygotowanie i wykonanie zapytania z użyciem prepared statement
        $statement = $pdo->prepare($query);
        $statement->bindParam(':id_pacjenta', $id_pacjenta, PDO::PARAM_INT);
        $statement->bindParam(':id_diagnozy', $id_diagnozy, PDO::PARAM_INT);
        $statement->bindParam(':zalecane_leczenie', $zalecane_leczenie, PDO::PARAM_STR);
        $statement->bindParam(':data_rozpoczecia', $data_rozpoczecia, PDO::PARAM_STR);
        
        if ($statement->execute()) {
            // Przekierowanie użytkownika na stronę główną po dodaniu planu leczenia
            header("Location: index.html");
            exit();
        } else {
            // W przypadku błędu wyświetlenie komunikatu
            echo "Wystąpił błąd podczas dodawania planu leczenia.";
        }
    } catch (PDOException $e) {
        // Obsłuż błąd związany z wykonaniem zapytania SQL
        echo "Błąd wykonania zapytania SQL: " . $e->getMessage();
    }
}
?>

