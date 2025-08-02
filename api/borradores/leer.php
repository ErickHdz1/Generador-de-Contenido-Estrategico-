<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once("../db_config.php");

try {
    $query = "SELECT * FROM borradores";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $borradores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($borradores);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error al leer: " . $e->getMessage()]);
}
?>



