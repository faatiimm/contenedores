<?php
/**
 * Script de backend para la actualización segura de la contraseña del usuario.
 * Este proceso garantiza el cumplimiento del requisito de gestión de perfil del proyecto.
 */

// Inicia la sesión para validar la identidad del usuario activo
session_start();

// Carga la conexión a la base de datos MySQL
require_once "../database/connection.php";

/**
 * Verificación de seguridad inicial:
 * Comprueba que la solicitud sea POST y que el usuario esté autenticado.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["id_usuario"])) {
    
    // Captura de datos del formulario de cambio de credenciales
    $id = $_SESSION["id_usuario"];
    $actual = $_POST['pass_actual']; // Contraseña actual para validación de seguridad
    $nueva  = $_POST['pass_nueva'];  // Nueva contraseña propuesta
    $rep    = $_POST['pass_rep'];    // Confirmación de la nueva contraseña

    /**
     * Paso 1: Recuperación de la credencial almacenada.
     * Se consulta el hash de la contraseña actual en la base de datos para el usuario logueado.
     */
    $sql = "SELECT password FROM usuarios WHERE id_usuario = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    /**
     * Paso 2: Validación y Actualización.
     * password_verify: Compara la contraseña ingresada con el hash de la base de datos.
     * Se comprueba también que la nueva contraseña y su repetición coincidan.
     */
    if (password_verify($actual, $user['password']) && $nueva === $rep) {
        
        // Generación de un nuevo hash seguro para la nueva contraseña
        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        
        // Preparación de la consulta de actualización (UPDATE) en la tabla de usuarios
        $update = mysqli_prepare($conn, "UPDATE usuarios SET password = ? WHERE id_usuario = ?");
        mysqli_stmt_bind_param($update, "si", $hash, $id);
        mysqli_stmt_execute($update);
        
        // Redirección con parámetro de éxito al perfil
        header("Location: ../frontend/perfil.php?ok=pass");
    } else {
        // Redirección con parámetro de error si las validaciones fallan
        header("Location: ../frontend/perfil.php?err=pass");
    }
}
?>