<?php
$host = getenv("DB_HOST") ?: "db";
$user = getenv("DB_USER") ?: "userphp";
$pass = getenv("DB_PASS") ?: "badpasswd";
$db   = getenv("DB_NAME");
$port = getenv("DB_PORT");

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    throw new Exception("Error de conexión a la base de datos: " . $conn->connect_error);
}
?>
