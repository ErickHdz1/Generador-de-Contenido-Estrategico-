<?php
// Ruta corregida para db_config.php
require_once "../../db_config.php"; // Subir dos niveles para llegar a la raíz del proyecto

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT"); // Usamos el método PUT para actualizaciones
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Recibe la solicitud JSON del frontend
$data = json_decode(file_get_contents("php://input"));

// Verifica que todos los datos necesarios estén presentes, incluyendo el ID
if(empty($data->id) || empty($data->titulo) || empty($data->contenido) || empty($data->tipo_contenido) || empty($data->tono)){
    http_response_code(400);
    echo json_encode(array("message" => "Datos incompletos para actualizar. Se requieren 'id', 'titulo', 'contenido', 'tipo_contenido' y 'tono'."));
    exit();
}

$id = $data->id;
$titulo = $data->titulo;
$contenido = $data->contenido;
$tipo_contenido = $data->tipo_contenido;
$tono = $data->tono;

// Prepara la sentencia SQL para actualizar el borrador
// Usamos sentencias preparadas para prevenir inyección SQL
$sql = "UPDATE borradores SET titulo = ?, contenido = ?, tipo_contenido = ?, tono = ? WHERE id = ?";

if($stmt = $mysqli->prepare($sql)){
    // Vincula las variables a los parámetros de la sentencia preparada
    // "ssssi" significa: 4 strings (titulo, contenido, tipo_contenido, tono) y 1 entero (id)
    $stmt->bind_param("ssssi", $param_titulo, $param_contenido, $param_tipo_contenido, $param_tono, $param_id);
    
    $param_titulo = $titulo;
    $param_contenido = $contenido;
    $param_tipo_contenido = $tipo_contenido;
    $param_tono = $tono;
    $param_id = $id;
    
    // Intenta ejecutar la sentencia preparada
    if($stmt->execute()){
        // Verifica si alguna fila fue afectada (si el borrador realmente se actualizó)
        if ($stmt->affected_rows > 0) {
            http_response_code(200);
            echo json_encode(array("message" => "Borrador actualizado exitosamente."));
        } else {
            http_response_code(200); // OK, pero no se actualizó ninguna fila (quizás el ID no existe o no hubo cambios)
            echo json_encode(array("message" => "Borrador encontrado, pero no se realizaron cambios o el ID no existe."));
        }
    } else{
        // Si hay un error en la ejecución, devuelve el error de MySQL
        http_response_code(500);
        echo json_encode(array("message" => "Error al actualizar el borrador: " . $stmt->error));
    }
    
    // Cierra la sentencia
    $stmt->close();
} else {
    // Si hay un error al preparar la consulta, devuelve el error de MySQL
    http_response_code(500);
    echo json_encode(array("message" => "Error al preparar la consulta: " . $mysqli->error));
}

// Cierra la conexión a la base de datos
$mysqli->close();
?>

