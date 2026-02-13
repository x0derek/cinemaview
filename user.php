<?php include 'includes/header.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
$username = $_SESSION['username'];
$userID = $_SESSION['user_id'];
if(isset($_POST['change_name'])){
    $newName = mysqli_real_escape_string($conn, $_POST['name']);
    mysqli_query($conn, "UPDATE Users SET name = '$newName' WHERE username = '$username'");
}
if(isset($_POST['change_surname'])){
    $newSurname = mysqli_real_escape_string($conn, $_POST['surname']);
    mysqli_query($conn, "UPDATE Users SET surname = '$newSurname' WHERE username = '$username'");
}


if(isset($_POST['change_username'])){
    $newUsername = mysqli_real_escape_string($conn, $_POST['new_username']);
    
    if(mysqli_query($conn, "UPDATE Users SET username = '$newUsername' WHERE username = '$username'")){
        $_SESSION['username'] = $newUsername; 
        $username = $newUsername;
    }
}
$sql = "SELECT username, name, surname FROM Users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>

<h1 class="category user">Profil użytkownika</h1>
    <div class="srodek user">
    <span class="userinfo"><strong>Nazwa użytkownika:</strong><br> <?= htmlspecialchars($user['username']) ?></span>
    <span class="userinfo"><strong>Imię:</strong><br> <?= htmlspecialchars($user['name']) ?></span>
    <span class="userinfo"><strong>Nazwisko:</strong><br> <?= htmlspecialchars($user['surname']) ?></span>

    <label class="change">Zmień imię</label>
    <form method="POST">
        <input type="text" name="name" required>
        <input type="submit" name="change_name" value="Zmień">
    </form>

    <label class="change">Zmień nazwisko:</label>
    <form method="POST">
        <input type="text" name="surname" required>
        <input class="button" type="submit" name="change_surname" value="Zmień">
    </form>

    <label class="change">Zmień nazwę użytkownika</label>
    <form method="POST">
        <input type="text" name="new_username" required>
        <input class="button" type="submit" name="change_username" value="Zmień">
    </form>
    </div>

<h1 class="category user">Bilety użytkownika</h1>
    <div class="srodek user">
    <?php
    $sql_b = "SELECT f.title, b.row, b.seat
            FROM Bought_tickets AS b
            JOIN Tickets AS t ON t.id = b.tickets
            JOIN Films AS f ON f.id = t.film
            WHERE b.user = '$userID'";

    $result_b = mysqli_query($conn, $sql_b);

    if(mysqli_num_rows($result_b) > 0){
        echo "<table><tr><th>Film</th><th>Rząd</th><th>Miejsce</th></tr>";

        while($ticket = mysqli_fetch_assoc($result_b)){
            echo "<tr>";
            echo "<td>" . htmlspecialchars($ticket['title']) . "</td>";
            echo "<td>" . htmlspecialchars($ticket['row']) . "</td>";
            echo "<td>" . htmlspecialchars($ticket['seat']) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>Nie masz jeszcze żadnych zakupionych biletów.</p>";
    }
    ?>
    </div>
<?php require 'includes/footer.php';?>