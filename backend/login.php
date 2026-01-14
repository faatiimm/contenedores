<?php
/**
 * Script de backend para la autenticación de usuarios.
 * Gestiona el inicio de sesión verificando credenciales contra la base de datos.
 */

// Inicia la sesión para permitir el almacenamiento de datos del usuario autenticado 
session_start();

// Carga el archivo de conexión a la base de datos MySQL
require_once "../database/connection.php";

/**
 * Proceso de autenticación
 * Se ejecuta únicamente cuando se recibe una petición de tipo POST desde el formulario.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura y limpieza de los datos de acceso
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    /**
     * Paso 1: Localización del registro.
     * Consulta la tabla de usuarios buscando una coincidencia con el email proporcionado.
     */
    $sql = "SELECT id_usuario, nombre, email, password FROM usuarios WHERE email = ?";
    
    // Uso de sentencias preparadas para garantizar la seguridad del acceso a datos
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    /**
     * Paso 2: Validación de identidad.
     * Si el usuario existe, se procede a verificar la integridad de la contraseña.
     */
    if ($usuario = mysqli_fetch_assoc($result)) {
        
        // Compara la contraseña ingresada con el hash almacenado en la base de datos
        if (password_verify($password, $usuario['password'])) {
            
            /**
             * Paso 3: Establecimiento de la sesión[cite: 7].
             * Se almacenan datos clave en la superglobal $_SESSION para persistir la identidad 
             * del usuario a través de las diferentes páginas (perfil, feed, threads)[cite: 7].
             */
            $_SESSION['id_usuario'] = $usuario['id_usuario']; // ID único para relaciones con posts y comentarios [cite: 11, 14]
            $_SESSION['nombre'] = $usuario['nombre'];         // Nombre para visualización en el perfil [cite: 8]
            $_SESSION['email'] = $usuario['email'];           // Email del registro del usuario [cite: 8]

            // Redirección al Feed principal tras una autenticación exitosa [cite: 7, 9]
            header("Location: ../frontend/feed.php");
            exit();
        } else {
            // Error: La contraseña no coincide con el registro
            header("Location: ../frontend/login.php?error=credenciales");
            exit();
        }
    } else {
        // Error: El correo electrónico no figura en la base de datos
        header("Location: ../frontend/login.php?error=no_existe");
        exit();
    }
}