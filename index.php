<?php
/**
 * Este archivo actúa como un puente.
 * Cuando entras a la IP:8080, Apache lee este archivo primero
 * y te redirige automáticamente a la carpeta frontend.
 */
header("Location: ./frontend/index.php");
exit();
?>
