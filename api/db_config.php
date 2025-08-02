<?php
$host = "localhost";
$db_name = "contenido_estrategico";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $exception){
    echo "Error en la conexión: " . $exception->getMessage();
    exit;
}
?>

