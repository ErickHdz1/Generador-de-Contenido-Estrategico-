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

// Lógica para el método PUT (actualización)
if ($method === 'PUT') {
    // Validar que todos los datos necesarios estén presentes
    if(empty($data->id) || !isset($data->titulo) || !isset($data->contenido) || !isset($data->tipo_contenido) || !isset($data->tono)){
        http_response_code(400); // Bad Request
        echo json_encode(array("message" => "Datos incompletos para actualizar. Se requieren 'id', 'titulo', 'contenido', 'tipo_contenido' y 'tono'."));
        exit();
    }

    $id = $data->id;
    $titulo = $data->titulo;
    $contenido = $data->contenido;
    $tipo_contenido = $data->tipo_contenido;
    $tono = $data->tono;

    // Prepara la consulta SQL para actualizar el borrador de forma segura
    $sql = "UPDATE borradores SET titulo = ?, contenido = ?, tipo_contenido = ?, tono = ? WHERE id = ?";

    if($stmt = $mysqli->prepare($sql)){
        // Vincula los parámetros: 'ssssi' indica 4 strings y 1 entero
        $stmt->bind_param("ssssi", $param_titulo, $param_contenido, $param_tipo_contenido, $param_tono, $param_id);
        
        $param_titulo = $titulo;
        $param_contenido = $contenido;
        $param_tipo_contenido = $tipo_contenido;
        $param_tono = $tono;
        $param_id = $id;
        
        if($stmt->execute()){
            // Si la actualización fue exitosa y se afectó al menos una fila
            if ($stmt->affected_rows > 0) {
                http_response_code(200); // OK
                echo json_encode(array("message" => "Borrador actualizado exitosamente."));
            } else {
                // Mensaje mejorado: El borrador fue encontrado, pero no hubo cambios.
                http_response_code(200); // OK (la operación fue exitosa, pero sin cambios)
                echo json_encode(array("message" => "Borrador encontrado, pero los datos son los mismos o no hubo cambios."));
            }
        } else{
            http_response_code(500); // Internal Server Error
            echo json_encode(array("message" => "Error al actualizar el borrador: " . $stmt->error));
        }
        $stmt->close();
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Error al preparar la consulta: " . $mysqli->error));
    }
} else {
    // Si el método no es PUT, devuelve un error de método no permitido
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("message" => "Método no permitido. Se espera PUT o POST con _method=PUT."));
}

$mysqli->close(); // Cierra la conexión a la base de datos
?>
