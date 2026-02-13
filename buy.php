<?php
require 'includes/header.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    die('Musisz być zalogowany');
}

if (!isset($_GET['ticket']) || !is_numeric($_GET['ticket'])) {
    die("Brak seansu");
}

if (empty($_POST['seat'])) {
    die("Nie wybrano miejsc");
}

$ticketId = (int)$_GET['ticket'];
$userId   = $_SESSION['user_id'];
$seats    = $_POST['seat'];

$sql = $conn->prepare("SELECT id FROM Tickets WHERE id = ?");
$sql->bind_param("i", $ticketId);
$sql->execute();
$sql->store_result();

if ($sql->num_rows === 0) {
    die("Ten seans jest niedostępny");
}

$sql->close();

$sql = $conn->prepare(
    "INSERT INTO Bought_tickets (`user`, `tickets`, `row`, `seat`)
     VALUES (?, ?, ?, ?)"
);

foreach ($seats as $place) {
    list($row, $seat) = explode('/', $place);

    $row = (int)$row;
    $seat = (int)$seat;

    $sql->bind_param("iiii", $userId, $ticketId, $row, $seat);
    $sql->execute();
}

$sql->close();

echo "<h2>Bilety zakupione pomyślnie</h2>";
echo '<a href="index.php">Powrót</a>';

require 'includes/footer.php';
?>
