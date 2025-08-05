<?php
// Ruta corregida para db_config.php
require_once "../db_config.php";

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$sql = "SELECT id, titulo, contenido, tipo_contenido, tono, fecha_creacion FROM borradores ORDER BY fecha_creacion DESC";

$result = $mysqli->query($sql);
$drafts = array();

if ($result) {
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $drafts[] = $row;
        }
    }
    http_response_code(200);
    echo json_encode($drafts);
} else {
    http_response_code(500);
    echo json_encode(array("message" => "Error al leer borradores: " . $mysqli->error));
}

$mysqli->close();
?>


