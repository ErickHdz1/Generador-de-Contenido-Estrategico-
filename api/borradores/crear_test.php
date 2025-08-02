<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// Incluye el archivo de conexión
// La ruta es correcta según tu estructura
require_once("../db_config.php"); 


$apiKey = 'AIzaSyBZdNcOAQHg8rLWYuqf4V9YMbIgDhAwMqE'; 
// La URL del modelo es la que funciona para ti
$apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-latest:generateContent?key=" . $apiKey;

// Obtener datos JSON desde POST
$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->titulo) &&
    !empty($data->tipo_contenido) &&
    !empty($data->tono) &&
    !empty($data->tema_principal) 
) {
    try {
        // --- Lógica para llamar a la IA de Gemini ---
        $prompt = "Genera un borrador de un(a) {$data->tipo_contenido} con un tono {$data->tono} sobre el siguiente tema: {$data->tema_principal}. El formato de la respuesta debe ser un texto plano.";

        $payload = json_encode([
            "contents" => [
                "parts" => [
                    ["text" => $prompt]
                ]
            ]
        ]);

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            echo json_encode(["success" => false, "message" => "Error de cURL: " . $error]);
            exit;
        }

        $response_data = json_decode($response, true);
        $contenidoGenerado = "No se pudo generar el contenido."; 

        // Verificación de errores de la API de Gemini
        if (isset($response_data['error'])) {
            echo json_encode(["success" => false, "message" => "Error de Gemini API: " . $response_data['error']['message']]);
            exit;
        }

        // Obtener el contenido generado
        if (isset($response_data['candidates'][0]['content']['parts'][0]['text'])) {
            $contenidoGenerado = $response_data['candidates'][0]['content']['parts'][0]['text'];
        }

        // --- Fin de la lógica de la IA ---

        // Ahora, inserta el contenido generado en la base de datos
        $query = "INSERT INTO borradores (titulo, contenido, tipo_contenido, tono) 
                 VALUES (:titulo, :contenido, :tipo_contenido, :tono)";
        
        $stmt = $conn->prepare($query);

        $stmt->bindParam(":titulo", $data->titulo);
        $stmt->bindParam(":contenido", $contenidoGenerado); 
        $stmt->bindParam(":tipo_contenido", $data->tipo_contenido);
        $stmt->bindParam(":tono", $data->tono);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Borrador creado exitosamente.", "borrador" => $contenidoGenerado]);
        } else {
            echo json_encode(["success" => false, "message" => "No se pudo crear el borrador."]);
        }

    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error de base de datos: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Datos incompletos."]);
}
?>