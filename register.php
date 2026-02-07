<?php require 'includes/header.php';
if(isset($_SESSION['username'])){
        header('Location: index.php');
        exit;
    }
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $name = mysqli_real_escape_string($conn, $_POST["name"]);
        $surname = mysqli_real_escape_string($conn, $_POST["surname"]);
        $email = mysqli_real_escape_string($conn, $_POST["mail"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);
        $confirmPassword = mysqli_real_escape_string($conn, $_POST["confirmPassword"]);

        if($password !== $confirmPassword){
            $error = "Hasła różnią się.";
        } else{
            $sql = "SELECT * FROM users WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){
                $error = "Nazwa użytkownika jest zajęta.";
            } else{
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO Users (username, name, surname, mail, `password`) VALUES('$username', '$name', '$surname', '$email', '$hashedPassword')";

                if(mysqli_query($conn, $sql)){
                    echo "<p>Dodano użytkownika</p>";
                } else{
                    echo "<p>Nie udało się dodać użytkownika</p>";
                }
            }
        }

    }

    ?>
<div class="srodek">
    <h1>Rejestracja</h1>
        <div class="reglog">
        <form action="" method="POST">

            <label for="">Nazwa użytkownika:</label>
            <input type="text" name="username", id="username" required><br>

            <label for="">Imię:</label>
            <input type="text" name="name" id="name" required><br>

            <label for="">Nazwisko:</label>
            <input type="text" name="surname" id="surname" required><br>

            <label for="">Email:</label>
            <input type="email" name="mail" id="mail" required><br>

            <label for="">Hasło:</label>
            <input type="password" name="password" id="password" required><br>

            <label for="">Powtórz hasło:</label>
            <input type="password" name="confirmPassword" id="confirmPassword" required><br>

            <input type="submit" value="Zarejestruj">
        </form>
    </div>
</div>
<? require 'includes/footer.php';?>