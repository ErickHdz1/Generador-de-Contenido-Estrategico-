<?php
// Estos encabezados son cruciales para permitir la comunicación entre el navegador y el servidor
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Configuración de la base de datos
$host = "localhost";
$db_name = "contenido_estrategico";
$username = "root";
$password = "";

try {
    // Establecer la conexión a la base de datos con PDO (PHP Data Objects)
    // El "new PDO" es la forma estándar de crear una nueva conexión.
    $conn = new PDO("mysql:host={$host};dbname={$db_name};charset=utf8", $username, $password);
    
    // Configurar PDO para que lance excepciones en caso de error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si se ha recibido un ID en los parámetros de la URL (Método GET)
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];
        
        // Preparar la consulta SQL para evitar inyecciones de SQL (práctica de seguridad esencial)
        $query = "DELETE FROM borradores WHERE id = :id";
        $stmt = $conn->prepare($query);
        
        // Asignar el valor del ID al marcador de posición ":id" de la consulta
        // El PDO::PARAM_INT asegura que el valor se trate como un entero
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Si la eliminación fue exitosa, enviar una respuesta JSON de éxito
            echo json_encode(["success" => true, "message" => "Borrador eliminado correctamente."]);
        } else {
            // Si hubo un error en la ejecución, enviar una respuesta JSON de error con código HTTP 500
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error al eliminar el borrador."]);
        }
    } else {
        // Si no se proporcionó un ID, enviar un error con código HTTP 400
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ID no recibido."]);
    }

} catch (PDOException $e) {
    // Manejar cualquier error de conexión a la base de datos
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error de conexión: " . $e->getMessage()]);
}

// Nota: No es necesario cerrar la conexión PDO en un script que termina
// ya que PHP lo hace automáticamente, pero es una buena práctica en otros contextos.
$conn = null;
?>
