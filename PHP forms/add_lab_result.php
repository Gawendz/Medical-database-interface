<?php
// Pobranie danych uwierzytelniających bazy danych z zmiennych środowiskowych
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

// Sprawdzenie, czy formularz został wysłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobranie danych z formularza
    $id_pacjenta = $_POST['id_pacjenta'];
    $rodzaj_badania = $_POST['rodzaj_badania'];
    $wynik = $_POST['wynik'];
    $data = $_POST['data'];

    try {
        // Połączenie z bazą danych
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Zapytanie SQL w celu dodania wyniku lab do bazy danych
        $query = "INSERT INTO Wyniki_lab (id_pacjenta, rodzaj_badania, wynik, data) VALUES (:id_pacjenta, :rodzaj_badania, :wynik, :data)";

        // Przygotowanie i wykonanie zapytania przy użyciu PDO
        $statement = $pdo->prepare($query);
        $statement->bindParam(':id_pacjenta', $id_pacjenta, PDO::PARAM_INT);
        $statement->bindParam(':rodzaj_badania', $rodzaj_badania, PDO::PARAM_STR);
        $statement->bindParam(':wynik', $wynik, PDO::PARAM_STR);
        $statement->bindParam(':data', $data, PDO::PARAM_STR);
        
        if ($statement->execute()) {
            // Przekierowanie użytkownika po pomyślnym dodaniu wyniku lab
            header("Location: index.html");
            exit();
        } else {
            // W przypadku błędu wyświetlenie komunikatu
            echo "Wystąpił błąd podczas dodawania wyniku lab.";
        }
    } catch (PDOException $e) {
        // Obsłuż błąd związany z wykonaniem zapytania SQL
        echo "Błąd wykonania zapytania SQL: " . $e->getMessage();
    }
}
?>

