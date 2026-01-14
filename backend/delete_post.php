<?php
/**
 * Script de backend para la eliminación segura de publicaciones (posts).
 * Este archivo asegura que solo el autor de una publicación pueda eliminarla,
 * manteniendo la integridad de la base de datos y la relación usuario-post.
 */

// Inicia la sesión para validar la identidad del usuario activo
session_start();

// Importa la configuración de la conexión a la base de datos
require_once "../database/connection.php";

/**
 * Paso 1: Control de Acceso.
 * Verifica que el usuario tenga una sesión activa. Si no es así, redirige al login.
 */
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../frontend/login.php");
    exit();
}

/** * Paso 2: Captura de Parámetros.
 * Obtiene el ID del post a eliminar desde la URL (GET) y el ID del usuario desde la sesión.
 */
$id_post = $_GET['id'] ?? null;
$id_usuario_sesion = $_SESSION['id_usuario'];

if ($id_post) {
    /**
     * Paso 3: Validación de Propiedad (Seguridad Crítica).
     * Antes de borrar, se consulta si el post realmente pertenece al usuario en sesión.
     * Esto evita que un usuario borre publicaciones ajenas modificando el ID en la URL.
     */
    $sql_check = "SELECT id_usuario FROM posts WHERE id_post = ? AND id_usuario = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "ii", $id_post, $id_usuario_sesion);
    mysqli_stmt_execute($stmt_check);
    $resultado = mysqli_stmt_get_result($stmt_check);

    // Si la consulta devuelve una fila, el usuario es el dueño legítimo
    if (mysqli_num_rows($resultado) > 0) {
        
        /**
         * Paso 4: Ejecución del Borrado.
         * Se procede a eliminar el registro de la tabla 'posts'.
         * Nota técnica: Para evitar errores de integridad, la base de datos debe estar 
         * configurada con ON DELETE CASCADE para los comentarios asociados.
         */
        $sql_delete = "DELETE FROM posts WHERE id_post = ?";
        $stmt_del = mysqli_prepare($conn, $sql_delete);
        mysqli_stmt_bind_param($stmt_del, "i", $id_post);
        
        if (mysqli_stmt_execute($stmt_del)) {
            // Redirección al perfil con mensaje de éxito mediante parámetro GET
            header("Location: ../frontend/perfil.php?delete=success");
        } else {
            // Manejo de errores en la ejecución de la consulta SQL
            header("Location: ../frontend/perfil.php?delete=error");
        }
    } else {
        /**
         * Intento de acceso no autorizado:
         * El post no existe o el ID de usuario no coincide con el autor.
         */
        header("Location: ../frontend/perfil.php?delete=denied");
    }
} else {
    // Si no se proporciona un ID de post, simplemente se regresa al perfil
    header("Location: ../frontend/perfil.php");
}
exit();