<?php
// Pobranie danych uwierzytelniających bazy danych z zmiennych środowiskowych
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

// Sprawdzenie czy formularz został przesłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobranie danych z formularza
    $patientId = $_POST['patientId'];
    $medicationName = $_POST['medicationName'];
    $dosage = $_POST['dosage'];

    try {
        // Połączenie z bazą danych
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Zapytanie SQL do dodania nowego leku
        $query = "INSERT INTO Leki (ID_Pacjenta, Nazwa_leku, Dawkowanie) VALUES (:patientId, :medicationName, :dosage)";
        
        // Przygotowanie i wykonanie zapytania z użyciem prepared statement
        $statement = $pdo->prepare($query);
        $statement->bindParam(':patientId', $patientId, PDO::PARAM_INT);
        $statement->bindParam(':medicationName', $medicationName, PDO::PARAM_STR);
        $statement->bindParam(':dosage', $dosage, PDO::PARAM_STR);
        
        if ($statement->execute()) {
            // Przekierowanie użytkownika po dodaniu leku
            header("Location: index.html");
            exit();
        } else {
            // W przypadku błędu wyświetlenie komunikatu
            echo "Wystąpił błąd podczas dodawania leku.";
        }
    } catch (PDOException $e) {
        // Obsłuż błąd związany z wykonaniem zapytania SQL
        echo "Błąd wykonania zapytania SQL: " . $e->getMessage();
    }
}
?>

