<?php
$server = "localhost";
$user = "root";
$pass = "";
$base = "echo";

try{
    $conn = mysqli_connect($server, $user, $pass, $base);
} catch (Exception $e){
    die ("Połączenie nie powiodło się. ". $e->getMessage());
}

$error = "";

if($error){
        echo "<p style = 'color: red'>{$error}</p>";
    }
?>