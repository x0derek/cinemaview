<?php include 'includes/header.php';
if(isset($_SESSION['username'])){
        header('Location: index.php');
        exit;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);
        
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) < 0){
                $error = "Nazwa użytkownika nie istnieje.";
            } else{
                //Główne Logowanie
                $user = mysqli_fetch_assoc($result);
                if(password_verify($password, $user["password"])){
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $user['username'];
                    header("Location: index.php");
                    exit;
            } else{
                $error = "Niepoprawne hasło.";
            }


                
    }
}
    
    ?>
<div class="srodek">
    <h1>Logowanie</h1>
    <div class="reglog">
        <form action="" method="POST">
            <label for="">Nazwa użytkownika:</label>
            <input type="text" name="username", id="username" required><br>

            <label for="">Hasło:</label>
            <input type="password" name="password" id="password" required><br>

<<<<<<< HEAD
        <input type="submit" value="Zaloguj">
    </form>
    </div>
    </div>
<?php include 'includes/footer.php'; ?>
=======
            <input type="submit" value="Zaloguj">
        </form>
    </div>
</div>
<?php require 'includes/footer.php';?>
>>>>>>> 9063b60 (Zaktualizowano styl logowania/rejestracji, dodano styl scrollbara, naprawiono błędy)
