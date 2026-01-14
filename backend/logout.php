<?php
/**
 * Script de backend para el cierre de sesión (Logout).
 * Este archivo se encarga de limpiar el rastro del usuario en el servidor y 
 * asegurar que el acceso a las áreas restringidas quede revocado.
 */

// Reanuda la sesión actual para poder manipular sus datos y posteriormente eliminarlos
session_start();

/**
 * Paso 1: Limpieza de variables.
 * Se sobrescribe el array $_SESSION con un array vacío para eliminar 
 * datos sensibles como el 'id_usuario' o el 'nombre'.
 */
$_SESSION = array();

/**
 * Paso 2: Eliminación de Cookies de sesión.
 * Si la configuración del servidor utiliza cookies para el ID de sesión, 
 * se procede a invalidar la cookie en el navegador del cliente restando tiempo a su expiración.
 */
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

/**
 * Paso 3: Destrucción de la sesión en el servidor.
 * Elimina definitivamente el archivo de sesión almacenado en el servidor, 
 * completando el ciclo de vida de la sesión actual.
 */
session_destroy();

/**
 * Paso 4: Redirección final.
 * Una vez cerrada la sesión, se redirige al usuario a la página de inicio (index.php),
 * la cual permite identificar rápidamente la temática del foro según los requisitos.
 */
header("Location: ../frontend/index.php");
exit();