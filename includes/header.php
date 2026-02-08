<<<<<<< HEAD
<?php session_start(); ?>
=======
<?php
session_start();

?>

>>>>>>> 38da492 (Dodanie panelu administracyjnego i wyswietlanie filmow na aktualny dzien)
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<nav>
    <a href="index.php" id="nazwa">CinemaView</a>
    <ul>
        <?php if(isset($_SESSION['username'])): ?>
            <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true): ?>
                <li><a href="admin.php">Panel Administratora</a></li>
            <?php endif; ?>
            <li><a href="profile.php">Profil (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            <li><a href="logout.php">Wyloguj się</a></li>
        <?php else: ?>
            <li><a href="login.php">Zaloguj się</a></li>
            <li><a href="register.php">Zarejestruj się</a></li>
        <?php endif; ?>
    </ul>
</nav>

<?php
try {
    $conn = mysqli_connect('localhost', 'echo', 'iGYjNG3ghbQnK8u', 'echo');
} catch (Exception $e) {
    die("Połączenie nie powiodło się. " . $e->getMessage());
}
<<<<<<< HEAD
=======
mysqli_set_charset($conn, "utf8mb4");
>>>>>>> 38da492 (Dodanie panelu administracyjnego i wyswietlanie filmow na aktualny dzien)
?>

<div id="main">
