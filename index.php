<?php include 'includes/header.php'; ?>

<div id="news">
    <span id="title">Nowości w CinemaView:</span>
    <div id="move-panel"></div>
</div>

<div id="repertuar">
    <span id="title">Repertuar:</span>
    <section>
            <?php
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
</div>
<?php include 'includes/footer.php'; ?>