<?php
// Ruta para db_config.php: '../' sube un nivel de 'api/borradores/' a la raíz de htdocs.
require_once "../db_config.php";

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
// Permitir GET, POST, PUT, DELETE, OPTIONS para el preflight, la lógica interna usará POST para PUT/DELETE simulados.
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Manejar la solicitud OPTIONS (preflight request de CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); // Termina la ejecución aquí para el preflight
}

// Recibe el cuerpo de la solicitud JSON
$data = json_decode(file_get_contents("php://input"));

// Determinar el método HTTP real (incluyendo el workaround _method)
// Si la solicitud es POST y contiene un _method, usa ese _method.
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST' && isset($data->_method)) {
    $method = strtoupper($data->_method);
}

// Lógica para el método DELETE (eliminación)
if ($method === 'DELETE') {
    // Validar que el ID esté presente
    if(empty($data->id)){
        http_response_code(400); // Bad Request
        echo json_encode(array("message" => "Datos incompletos. Se requiere 'id'."));
        exit();
    }

    $id = $data->id;

    // Prepara la consulta SQL para eliminar de forma segura
    $sql = "DELETE FROM borradores WHERE id = ?";

    if($stmt = $mysqli->prepare($sql)){
        // Vincula el parámetro: 'i' indica un entero
        $stmt->bind_param("i", $param_id);
        
        $param_id = $id;
        
        if($stmt->execute()){
            // Si la eliminación fue exitosa
            if ($stmt->affected_rows > 0) {
                http_response_code(200); // OK
                echo json_encode(array("message" => "Borrador eliminado exitosamente."));
            } else {
                // Si no se afectaron filas, puede ser que el ID no exista
                http_response_code(404); // Not Found
                echo json_encode(array("message" => "Borrador no encontrado."));
            }
        } else{
            http_response_code(500); // Internal Server Error
            echo json_encode(array("message" => "Error al eliminar el borrador: " . $stmt->error));
        }
        $stmt->close();
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Error al preparar la consulta: " . $mysqli->error));
    }
} else {
    // Si el método no es DELETE, devuelve un error de método no permitido
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("message" => "Método no permitido. Se espera DELETE o POST con _method=DELETE."));
}

$mysqli->close(); // Cierra la conexión a la base de datos
?>
