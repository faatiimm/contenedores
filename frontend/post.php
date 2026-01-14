<?php
/**
 * FINALIDAD: Visualización detallada de un post específico y sus comentarios asociados.
 * REQUISITO: "Los usuarios podrán ver el contenido completo de un hilo y las respuestas de otros".
 */

session_start();

/** * CAPTURA Y SANITIZACIÓN DEL ID
 * Se utiliza intval() para asegurar que el parámetro recibido por GET sea un entero,
 * previniendo ataques de manipulación de tipos o inyecciones básicas.
 */
$id_post = intval($_GET["id"]);

// ...existing code fetching $post and $comentarios...

/**
 * VALIDACIÓN DE EXISTENCIA
 * Si la consulta no devuelve ningún resultado (el post no existe en la DB),
 * se redirige al feed para evitar que el usuario vea una página vacía.
 */
if (!$post) {
    header("Location: feed.php");
    exit;
}

// Cierre de la lógica PHP para iniciar la renderización de la interfaz
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($post["titulo"], ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>

<h2><?php echo htmlspecialchars($post["titulo"], ENT_QUOTES, 'UTF-8'); ?></h2>

<p><?php echo nl2br(htmlspecialchars($post["contenido"], ENT_QUOTES, 'UTF-8')); ?></p>

<p><em>Publicado por <?php echo htmlspecialchars($post["nombre"], ENT_QUOTES, 'UTF-8'); ?></em></p>

<hr>

<h3>Comentarios</h3>



<?php 
/**
 * BUCLE DE RENDERIZACIÓN DE COMENTARIOS
 * Itera sobre el conjunto de resultados de la tabla 'comentarios' vinculados a este id_post.
 */
while ($c = mysqli_fetch_assoc($comentarios)) { ?>
    <p>
        <strong><?php echo htmlspecialchars($c["nombre"], ENT_QUOTES, 'UTF-8'); ?>:</strong>
        <?php echo nl2br(htmlspecialchars($c["contenido"], ENT_QUOTES, 'UTF-8')); ?>
    </p>
<?php } ?>

<footer class="site-footer">
    <div class="container footer-inner">
        <div class="footer-info-block">
            <div class="brand-name" style="color: var(--accent); font-size: 0.9rem; margin-bottom: 5px;">
                RETROGAMING HUB
            </div>
            <p class="meta-text" style="font-size: 0.75rem; max-width: 250px;">
                Plataforma de preservación y discusión sobre sistemas de entretenimiento vintage.
            </p>
        </div>

        <div class="footer-info-block" style="text-align: center;">
            <div class="status-indicator">
                <span class="status-dot"></span>
                <span class="meta-text" style="font-size: 0.7rem; font-family: var(--mono);">SYSTEM_STATUS: ONLINE</span>
            </div>
            <p class="meta-text" style="font-size: 0.7rem; opacity: 0.5; margin-top: 5px;">
                XAMPP SERVER • PHP 8.2 • MYSQL
            </p>
        </div>

        <div class="footer-info-block" style="text-align: right;">
            <div class="badge" style="font-size: 0.65rem; letter-spacing: 1px;">BUILD 2026.01.10</div>
            <p class="meta-text" style="font-size: 0.7rem; margin-top: 5px;">
                © UT3 Foro Web Project
            </p>
        </div>
    </div>
</footer>
</body>
</html>