<?php
/**
 * Script de backend para la actualización del perfil de usuario.
 * Gestiona la modificación del nombre de usuario en la base de datos y
 * sincroniza la información con la sesión activa.
 */

// Inicia la sesión para validar la identidad del usuario y actualizar sus datos globales
session_start();

// Carga el archivo de configuración para la conexión con la base de datos MySQL
require_once "../database/connection.php";

/**
 * Verificación de seguridad y origen:
 * Comprueba que el acceso sea mediante el método POST y que el usuario esté autenticado.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["id_usuario"])) {
    
    // Captura del ID de usuario desde la sesión y del nuevo nombre desde el formulario
    $id_usuario = $_SESSION["id_usuario"];
    $nuevo_nombre = trim($_POST['nuevo_nombre']); // Limpieza de espacios en blanco

    /**
     * Paso 1: Validación de integridad.
     * Asegura que el campo enviado no esté vacío antes de interactuar con la base de datos.
     */
    if (!empty($nuevo_nombre)) {
        
        /**
         * Paso 2: Preparación de la consulta SQL (UPDATE).
         * Se utiliza una sentencia preparada para proteger el sistema contra Inyecciones SQL.
         */
        $sql = "UPDATE usuarios SET nombre = ? WHERE id_usuario = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        // Vinculación de parámetros: "si" representa un string (nombre) y un integer (ID)
        mysqli_stmt_bind_param($stmt, "si", $nuevo_nombre, $id_usuario);

        /**
         * Paso 3: Ejecución y sincronización.
         */
        if (mysqli_stmt_execute($stmt)) {
            /**
             * Actualización de la variable de sesión:
             * Es crucial actualizar $_SESSION['nombre'] para que los cambios se reflejen 
             * de inmediato en la cabecera y el perfil sin necesidad de re-iniciar sesión.
             */
            $_SESSION['nombre'] = $nuevo_nombre;

            // Redirección exitosa con notificación mediante parámetro GET
            header("Location: ../frontend/perfil.php?ok=actualizado");
            exit();
        } else {
            // Manejo de errores a nivel de base de datos
            header("Location: ../frontend/perfil.php?err=db");
            exit();
        }
    } else {
        // Notificación en caso de que el usuario envíe un campo vacío
        header("Location: ../frontend/perfil.php?err=vacio");
        exit();
    }
} else {
    /**
     * Protección contra acceso directo:
     * Si no existe sesión o la petición no es POST, se redirige al login.
     */
    header("Location: ../frontend/login.php");
    exit();
}