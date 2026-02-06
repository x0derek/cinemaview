<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona główna kino</title>
</head>
<body>
    <header>
        <h1>Kino przykładowy tekst</h1>
    </header>
    <p>tutaj nawigacja</p>
    <main>
        <p>Najwyżej oceniane filmy</p>
    </main>
    <section>
        <section>
           <?php
                include 'includes/header.php';

                $conn = new mysqli($host, $user, $password, $dbname);

                if ($conn->connect_error) {
                    die("Błąd połączenia: " . $conn->connect_error);
                }

                $sql = "SELECT id, title FROM films ORDER BY title ASC";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($film = $result->fetch_assoc()) {
                        echo '<div class="film-container">';
                        echo '<h3>' . htmlspecialchars($film["title"]) . '</h3>';
                        echo '<a href="filmy.php" class="przycisk_kup">Kup bilet</a>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>Brak filmów w bazie.</p>";
                }

                $conn->close();
            ?>

</section>

    </section>
</body>
</html>