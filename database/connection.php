<?php
/**
 * Configuración de la conexión a la Base de Datos.
 * Este archivo actúa como el puente de comunicación entre el servidor de scripts (PHP)
 * y el gestor de base de datos (MySQL/MariaDB).
 */

// Parámetros de configuración del servidor de base de datos
$host = getenv("DB_HOST");        // Dirección IP del servidor local (Loopback)
$user = getenv("DB_USER");             // Usuario administrador por defecto en XAMPP
$pass = getenv("DB_PASS");                 // Contraseña (vacía por defecto en entornos de desarrollo local)
$db   = getenv("DB_NAME");  // Nombre de la base de datos que contiene las tablas del foro
$port = 3307;               // Puerto estándar de comunicación para el servicio MySQL

/**
 * Establecimiento de la conexión mediante la extensión MySQLi.
 * Se incluyen los 5 parámetros necesarios para garantizar la precisión de la ruta.
 */
 $conn = new mysqli($host, $user, $pass,$db);
 $users = array();
 if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
 } else {
     $sql = 'SELECT * FROM users';
     
     if ($result = $conn->query($sql)) {
         while ($data = $result->fetch_object()) {
             $users[] = $data;
         }
     }
     
     foreach ($users as $user) {
        echo "<br>";
        echo $user->username . " " . $user->password;
        echo "<br>";
    }
 }
 mysqli_close($conn);
?>