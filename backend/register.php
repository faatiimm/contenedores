<?php
/**
 * Script de backend para el procesamiento del registro de nuevos usuarios.
 * Este archivo gestiona la inserción de datos en la tabla 'usuarios' de la base de datos.
 */

// Importa la configuración de la conexión a la base de datos MySQL
require_once "../database/connection.php";

/**
 * Lógica de registro
 * Se ejecuta tras recibir una petición POST desde el formulario de registro.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Captura y limpieza de datos recibidos del formulario
    $nombre = trim($_POST['usuario']);
    $email = trim($_POST['email']);

    // Validación básica: Verifica que las contraseñas coincidan
    $pass1 = $_POST['password'];
    $pass2 = $_POST['password2'];

    if ($pass1 !== $pass2) {
        header("Location: ../frontend/registro.php?error=passwords_no_match");
        exit();
    }   
    /**
     * Seguridad de contraseñas:
     * Se utiliza password_hash con el algoritmo PASSWORD_DEFAULT. 
     * Esto genera un hash seguro, cumpliendo con los estándares de protección de datos.
     */
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    /**
     * Bloque de control de excepciones (try-catch)
     * Se utiliza para gestionar errores de la base de datos, como registros duplicados.
     */
    try {
        // Preparación de la consulta SQL para insertar un nuevo usuario
        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        
        // Vinculación de los tres parámetros de tipo cadena (string)
        mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $password);
        
        // Ejecución de la consulta de inserción
        if (mysqli_stmt_execute($stmt)) {
            /**
             * Caso de Éxito: 
             * Redirige al inicio de sesión informando que el registro fue correcto.
             */
            header("Location: ../frontend/login.php?reg=success");
            exit();
        }
        
    } catch (mysqli_sql_exception $e) {
        /**
         * Gestión de errores específicos de SQL:
         * El código 1062 identifica una entrada duplicada (ej: el email ya está en uso).
         */
        if ($e->getCode() == 1062) {
            header("Location: ../frontend/registro.php?error=email_exists");
            exit();
        } else {
            // Manejo de errores genéricos de conexión o sintaxis en la base de datos
            header("Location: ../frontend/registro.php?error=db_error");
            exit();
        }
    }
}
?>
