<?php
/**
 * Script de backend para la creación de nuevas publicaciones (posts).
 * Este archivo gestiona la inserción de datos en la base de datos vinculando el post con su creador.
 */

// Inicia la sesión para validar el acceso y obtener el ID del autor
session_start();

// Carga el archivo de conexión a la base de datos MySQL
require_once "../database/connection.php";

/**
 * Verificación de seguridad: 
 * Comprueba que la petición sea de tipo POST y que el usuario haya iniciado sesión correctamente.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["id_usuario"])) {
    
    // Captura y limpieza de espacios en blanco de los datos enviados desde el formulario
    $titulo    = trim($_POST['titulo']);
    $categoria = $_POST['categoria']; // Recibe el valor del selector de plataforma
    $contenido = trim($_POST['contenido']);
    $id_usuario = $_SESSION['id_usuario']; // ID del usuario logueado (clave foránea)

    // Validación: Verifica que ningún campo obligatorio esté vacío antes de procesar
    if (!empty($titulo) && !empty($categoria) && !empty($contenido)) {
        
        /**
         * Definición de la consulta SQL.
         * Se relacionan las columnas de la tabla 'posts' con el ID del usuario creador.
         */
        $sql = "INSERT INTO posts (titulo, contenido, categoria, id_usuario) VALUES (?, ?, ?, ?)";
        
        // Preparación de la sentencia para prevenir ataques de Inyección SQL
        $stmt = mysqli_prepare($conn, $sql);
        
        /**
         * Vinculación de parámetros:
         * "sssi" indica que se envían tres cadenas (string) y un entero (integer)
         */
        mysqli_stmt_bind_param($stmt, "sssi", $titulo, $contenido, $categoria, $id_usuario);

        // Intento de ejecución de la inserción en la base de datos
        if (mysqli_stmt_execute($stmt)) {
            // En caso de éxito, redirige al usuario al feed para visualizar la publicación
            header("Location: ../frontend/feed.php");
            exit();
        } else {
            // Manejo de error técnico en la ejecución de la consulta
            echo "Error al crear el post: " . mysqli_error($conn);
        }
    } else {
        // Mensaje preventivo si faltan datos obligatorios en el formulario
        echo "Por favor, rellena todos los campos.";
    }
} else {
    /**
     * Si no hay una sesión activa, el sistema deniega el acceso a la funcionalidad
     * y redirige al usuario a la página de inicio de sesión.
     */
    header("Location: ../frontend/login.php");
    exit();
}