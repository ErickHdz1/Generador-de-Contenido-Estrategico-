<?php
// Configuración de la base de datos para InfinityFree
define('DB_SERVER', 'sql309.infinityfree.com'); // Hostname de MySQL de InfinityFree
define('DB_USERNAME', 'if0_39679855'); // Usuario de MySQL de InfinityFree
define('DB_PASSWORD', 'csQoHD4HDCI'); // Contraseña de la DB de InfinityFree
define('DB_NAME', 'if0_39679855_contenido_estrategico'); // Nombre COMPLETO de la DB de InfinityFree

// Intenta conectar a la base de datos de MySQL
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Revisa la conexión
if($mysqli->connect_error){
    // Si hay un error de conexión, termina el script y muestra el error
    die("ERROR: No se pudo conectar a la base de datos. " . $mysqli->connect_error);
}
?>

