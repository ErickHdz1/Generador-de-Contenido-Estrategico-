<?php
// Ruta para db_config.php: '../' sube un nivel de 'api/borradores/' a la raíz de htdocs.
require_once "../db_config.php";

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
// Permitir POST y OPTIONS para el preflight
header("Access-Control-Allow-Methods: POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Manejar la solicitud OPTIONS (preflight request de CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}

// Recibe el cuerpo de la solicitud JSON
$data = json_decode(file_get_contents("php://input"));

// Verifica que todos los datos necesarios estén presentes
if(empty($data->titulo) || empty($data->contenido) || empty($data->tipo_contenido) || empty($data->tono)){
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Datos incompletos. Se requieren 'titulo', 'contenido', 'tipo_contenido' y 'tono'."));
    exit();
}

$titulo = $data->titulo;
$contenido = $data->contenido;
$tipo_contenido = $data->tipo_contenido; 
$tono = $data->tono; 

// Prepara la sentencia SQL para insertar de forma segura
$sql = "INSERT INTO borradores (titulo, contenido, tipo_contenido, tono) VALUES (?, ?, ?, ?)";

if($stmt = $mysqli->prepare($sql)){
    // Vincula los parámetros: 'ssss' indica 4 strings
    $stmt->bind_param("ssss", $param_titulo, $param_contenido, $param_tipo_contenido, $param_tono);
    
    $param_titulo = $titulo;
    $param_contenido = $contenido;
    $param_tipo_contenido = $tipo_contenido; 
    $param_tono = $tono; 
    
    if($stmt->execute()){
        http_response_code(201); // Created
        echo json_encode(array("message" => "Borrador creado exitosamente."));
    } else{
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Error al crear el borrador: " . $stmt->error));
    }
    $stmt->close();
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error al preparar la consulta: " . $mysqli->error));
}

$mysqli->close(); // Cierra la conexión a la base de datos
?>
