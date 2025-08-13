<?php
// Ruta para db_config.php: '../' sube un nivel de 'api/borradores/' a la raíz de htdocs.
require_once "../db_config.php"; 

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
// Permitir GET y OPTIONS para el preflight
header("Access-Control-Allow-Methods: GET, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Manejar la solicitud OPTIONS (preflight request de CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}

// Prepara la consulta SQL para seleccionar borradores
$sql = "SELECT id, titulo, contenido, tipo_contenido, tono, fecha_creacion FROM borradores ORDER BY fecha_creacion DESC";

$result = $mysqli->query($sql);
$drafts = array();

if ($result) { 
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $drafts[] = $row;
        }
    }
    http_response_code(200); // OK
    echo json_encode($drafts);
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error al leer borradores: " . $mysqli->error));
}

$mysqli->close(); // Cierra la conexión a la base de datos
?>