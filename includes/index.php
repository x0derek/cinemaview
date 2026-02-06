<?php 
include 'includes/header.php';
?>

Strona główna

<?php
$result = $conn->query("SELECT id, title FROM films");
$filmy = $result->fetch_all(MYSQLI_ASSOC);
?>

<h1>Lista filmów</h1>

<ul>
<?php foreach ($filmy as $film): ?>
    <li>
        <a href="film.php?id=<?= $film['id'] ?>">
            <?= htmlspecialchars($film['title']) ?>
        </a>
    </li>
<?php endforeach; ?>
</ul>

<?php include 'includes/footer.php'; ?>
