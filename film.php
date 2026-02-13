<?php
require 'includes/header.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Brak ID filmu");
}

$filmId = (int)$_GET['id'];

$sql = $conn->prepare("SELECT * FROM Films WHERE id = ?");
$sql->bind_param("i", $filmId);
$sql->execute();
$film = $sql->get_result()->fetch_assoc();

if (!$film) {
    die("Film nie istnieje");
}

$sql = $conn->prepare("SELECT id FROM Tickets WHERE film = ?");
$sql->bind_param("i", $filmId);
$sql->execute();
$ticket = $sql->get_result()->fetch_assoc();

if (!$ticket) {
    die("Brak dostępnego seansu");
}

$ticketId = $ticket['id'];

$isLogged = !empty($_SESSION['logged_in']);

$sql = $conn->prepare(
    "SELECT `row`, `seat` FROM Bought_tickets WHERE tickets = ?"
);
$sql->bind_param("i", $ticketId);
$sql->execute();

$result = $sql->get_result();
$occupied = [];

while ($r = $result->fetch_assoc()) {
    $occupied[$r['row']][$r['seat']] = true;
}
?>

<img src='https://<?=htmlspecialchars($film['banner']) ?>'>
<img class="film vid" src='https://<?=htmlspecialchars($film['cover']) ?>'>
<h1 class="filmtitle"><?= htmlspecialchars($film['title']) ?></h1>

<p><b>Czas trwania:</b> <?= htmlspecialchars($film['time']) ?> min</p>
<p class="description"><?= nl2br(htmlspecialchars($film['description'])) ?></p>

<?php if (!$isLogged): ?>
    <p><b>Musisz być zalogowany, aby kupić bilety.</b></p>
<?php endif; ?>

<form method="post" action="buy.php?ticket=<?= $ticketId ?>">

<?php
for ($i = 10; $i >= 1; $i--) {
    for ($j = 1; $j <= 12; $j++) {

        $isTaken = isset($occupied[$i][$j]);

        echo '<label class="custom-checkbox '.($isTaken ? 'taken' : '').'">';
        echo '<input type="checkbox" name="seat[]" value="'.$i.'/'.$j.'" '
            .($isTaken ? 'disabled checked' : '')
            .(!$isLogged ? ' disabled' : '').'>';
        echo '<span></span>';
        echo '</label>';
    }
    echo "<br>";
}
?>

<div id="ekran">EKRAN</div>

<button class="button" type="submit" <?= !$isLogged ? 'disabled' : '' ?>>
    Kup bilety
</button>
</form>

<a href="index.php">← wróć</a>

<?php require 'includes/footer.php'; ?>
