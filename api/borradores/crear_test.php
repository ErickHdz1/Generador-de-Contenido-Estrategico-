<?php
// Ruta corregida para db_config.php
require_once "../db_config.php";

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));

if(empty($data->titulo) || empty($data->contenido) || empty($data->tipo_contenido) || empty($data->tono)){
    http_response_code(400);
    echo json_encode(array("message" => "Datos incompletos. Se requieren 'titulo', 'contenido', 'tipo_contenido' y 'tono'."));
    exit();
}

$titulo = $data->titulo;
$contenido = $data->contenido;
$tipo_contenido = $data->tipo_contenido;
$tono = $data->tono;

$sql = "INSERT INTO borradores (titulo, contenido, tipo_contenido, tono) VALUES (?, ?, ?, ?)";

if($stmt = $mysqli->prepare($sql)){
    $stmt->bind_param("ssss", $param_titulo, $param_contenido, $param_tipo_contenido, $param_tono);
    
    $param_titulo = $titulo;
    $param_contenido = $contenido;
    $param_tipo_contenido = $tipo_contenido;
    $param_tono = $tono;
    
    if($stmt->execute()){
        http_response_code(201);
        echo json_encode(array("message" => "Borrador creado exitosamente."));
    } else{
        http_response_code(500);
        echo json_encode(array("message" => "Error al crear el borrador: " . $stmt->error));
    }
    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(array("message" => "Error al preparar la consulta: " . $mysqli->error));
}

$mysqli->close();
?>

