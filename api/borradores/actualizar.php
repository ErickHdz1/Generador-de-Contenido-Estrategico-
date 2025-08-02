<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Conexión a la base de datos
$host = "localhost";
$db_name = "contenido_estrategico";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents("php://input"));

    if (
        !empty($data->id) &&
        !empty($data->titulo) &&
        !empty($data->contenido) &&
        !empty($data->tipo_contenido) &&
        !empty($data->tono)
    ) {
        $query = "UPDATE borradores 
                  SET titulo = :titulo, contenido = :contenido, tipo_contenido = :tipo_contenido, tono = :tono 
                  WHERE id = :id";

        $stmt = $conn->prepare($query);

        $stmt->bindParam(':id', $data->id);
        $stmt->bindParam(':titulo', $data->titulo);
        $stmt->bindParam(':contenido', $data->contenido);
        $stmt->bindParam(':tipo_contenido', $data->tipo_contenido);
        $stmt->bindParam(':tono', $data->tono);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Borrador actualizado correctamente."]);
        } else {
            echo json_encode(["success" => false, "message" => "No se pudo actualizar el borrador."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Datos incompletos."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>

