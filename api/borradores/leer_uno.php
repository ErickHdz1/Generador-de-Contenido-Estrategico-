<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$db_name = "contenido_estrategico";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el ID desde la URL
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $query = "SELECT * FROM borradores WHERE id = :id LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    $borrador = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($borrador) {
        echo json_encode($borrador);
    } else {
        echo json_encode(["success" => false, "message" => "Borrador no encontrado."]);
    }

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>

