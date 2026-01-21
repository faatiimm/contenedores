<?php
$host = getenv("DB_HOST") ?: "db";
$user = getenv("DB_USER") ?: "userphp";
$pass = getenv("DB_PASS") ?: "123456";
$db   = getenv("DB_NAME");
$port = getenv("DB_PORT");

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
