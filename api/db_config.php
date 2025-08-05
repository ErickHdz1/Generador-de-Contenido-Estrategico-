<?php
// Configuración de la base de datos
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Tu nombre de usuario de la base de datos
define('DB_PASSWORD', '');     // Tu contraseña de la base de datos
define('DB_NAME', 'contenido_estrategico'); // <--- ¡NOMBRE DE LA BASE DE DATOS CORREGIDO!

// Intenta conectar a la base de datos de MySQL
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Revisa la conexión
if($mysqli->connect_error){
    // Si hay un error de conexión, termina el script y muestra el error
    die("ERROR: No se pudo conectar a la base de datos. " . $mysqli->connect_error);
}
?>
