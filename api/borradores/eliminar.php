<?php
// Ruta corregida para db_config.php
require_once "../db_config.php";

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));

if(empty($data->id)){
    http_response_code(400);
    echo json_encode(array("message" => "Datos incompletos. Se requiere 'id'."));
    exit();
}

$id = $data->id;

$sql = "DELETE FROM borradores WHERE id = ?";

if($stmt = $mysqli->prepare($sql)){
    $stmt->bind_param("i", $param_id);
    
    $param_id = $id;
    
    if($stmt->execute()){
        http_response_code(200);
        echo json_encode(array("message" => "Borrador eliminado exitosamente."));
    } else{
        http_response_code(500);
        echo json_encode(array("message" => "Error al eliminar el borrador: " . $stmt->error));
    }
    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(array("message" => "Error al preparar la consulta: " . $mysqli->error));
}

$mysqli->close();
?>

