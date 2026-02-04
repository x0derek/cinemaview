<?php 
include 'includes/header.php';

if (!isset($_GET['id'])) {
    die("Brak ID filmu");
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM films WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$film = $result->fetch_assoc();

if (!$film) {
    die("Film nie istnieje");
}
?>

<h1><?= htmlspecialchars($film['title']) ?></h1>
<p><strong>Rok:</strong> <?= htmlspecialchars($film['year']) ?></p>
<p><?= nl2br(htmlspecialchars($film['description'])) ?></p>

<a href="index.php">← wróć do listy</a>
