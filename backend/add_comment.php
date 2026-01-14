<?php
// Inicia o reanuda la sesión del usuario para acceder a sus datos globales
session_start();

// Importa la configuración de la conexión a la base de datos
require_once "../database/connection.php";

/**
 * Lógica de inserción de comentarios
 * Verifica que la petición sea POST y que exista una sesión de usuario activa
 */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["id_usuario"])) {
    
    // Captura de datos enviados por el formulario y la sesión
    $id_post = $_POST['id_post'];
    $id_usuario = $_SESSION['id_usuario']; // Vinculación del comentario con su creador
    $contenido = trim($_POST['contenido']);

    // Validación de que el campo de contenido no esté vacío
    if (!empty($contenido)) {
        
        // Preparación de la consulta SQL para insertar en la tabla de comentarios
        $sql = "INSERT INTO comentarios (contenido, id_usuario, id_post) VALUES (?, ?, ?)";
        
        // Uso de sentencias preparadas para mitigar ataques de inyección SQL
        $stmt = mysqli_prepare($conn, $sql);
        
        // Vinculación de parámetros (String para contenido, Integer para IDs)
        mysqli_stmt_bind_param($stmt, "sii", $contenido, $id_usuario, $id_post);

        // Ejecución de la consulta en la base de datos
        if (mysqli_stmt_execute($stmt)) {
            // Redirección exitosa: devuelve al usuario al hilo específico donde comentó
            header("Location: ../frontend/thread.php?id=" . $id_post);
            exit();
        }
    }
}

/** * Manejo de errores o accesos no autorizados:
 * Si la petición no es válida o el usuario no está logueado, redirige al feed global
 */
header("Location: ../frontend/feed.php");
exit();