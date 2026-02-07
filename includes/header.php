<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
<nav>
    <a href="index.php" id="nazwa">CinemaView</a>
    <ul>
        <?php if(isset($_SESSION['username'])): ?>
            <li><a href="profile.php">Profil (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            <li><a href="logout.php">Wyloguj się</a></li>
        <?php else: ?>
            <li><a href="login.php">Zaloguj się</a></li>
            <li><a href="register.php">Zarejestruj się</a></li>
        <?php endif; ?>
    </ul>
</nav>
<?php
try{
    $conn = mysqli_connect('localhost', 'echo', 'iGYjNG3ghbQnK8u', '');
} catch (Exception $e){
    die ("Połączenie nie powiodło się. ". $e->getMessage());
}
?>
</head>
<div id="main">