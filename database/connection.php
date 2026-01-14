<?php
/**
 * Configuración de la conexión a la Base de Datos.
 * Este archivo actúa como el puente de comunicación entre el servidor de scripts (PHP)
 * y el gestor de base de datos (MySQL/MariaDB).
 */

// Parámetros de configuración del servidor de base de datos
$host = "127.0.0.1";        // Dirección IP del servidor local (Loopback)
$user = "root";             // Usuario administrador por defecto en XAMPP
$pass = "";                 // Contraseña (vacía por defecto en entornos de desarrollo local)
$db   = "retrogaming_hub";  // Nombre de la base de datos que contiene las tablas del foro
$port = 3306;               // Puerto estándar de comunicación para el servicio MySQL

/**
 * Establecimiento de la conexión mediante la extensión MySQLi.
 * Se incluyen los 5 parámetros necesarios para garantizar la precisión de la ruta.
 */
$conn = mysqli_connect($host, $user, $pass, $db, $port);

/**
 * Control de errores de conexión.
 * Si la conexión falla, se detiene la ejecución del script y se muestra el motivo técnico.
 * Es vital para el diagnóstico durante el desarrollo (Debug).
 */
if (!$conn) {
    die("Fallo de conexión: " . mysqli_connect_error());
}

// Opcional: Configuración del conjunto de caracteres a UTF-8 para evitar errores con tildes y eñes
mysqli_set_charset($conn, "utf8mb4");
?>