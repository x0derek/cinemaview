<?php include 'includes/header.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;

}

$username = $_SESSION['username'];
?>

<h1>Profil użytkownika: <?= htmlspecialchars($username) ?></h1>

<?php require 'includes/footer.php';?>