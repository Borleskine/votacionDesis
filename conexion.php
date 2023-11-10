<?php
// Definir constantes
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'votaciondesis');

// Crear conexión a la base de datos
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Manejar errores de conexión
if ($mysqli->connect_errno) {
    error_log('Conexion Fallida: ' . $mysqli->connect_error);
    exit();
}

// Establecer el conjunto de caracteres
$mysqli->set_charset('utf8');

// ... Realizar operaciones con la base de datos ...

// Cerrar la conexión cuando ya no sea necesaria
$mysqli->close();
?>

