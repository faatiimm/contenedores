<?php
/**
 * THREAD.PHP - Visualización detallada de un Post y sus comentarios.
 * Este archivo gestiona la recuperación relacional de datos de la base de datos.
 */
session_start();
require_once "../database/connection.php";

// 1. GESTIÓN DE PARÁMETROS Y SESIÓN
// Recuperamos el ID del post por URL. Si no existe, redirigimos para evitar errores de ejecución.
$id_post = $_GET['id'] ?? null;
$loggedIn = isset($_SESSION["id_usuario"]); 

if (!$id_post) {
    header("Location: feed.php");
    exit;
}

// 2. RECUPERACIÓN DEL POST PRINCIPAL
// Realizamos un JOIN con la tabla 'usuarios' para obtener el nombre del autor original.
// Se utiliza una SENTENCIA PREPARADA por seguridad contra Inyecciones SQL.
$sql_post = "SELECT p.*, u.nombre FROM posts p JOIN usuarios u ON p.id_usuario = u.id_usuario WHERE p.id_post = ?";
$stmt_p = mysqli_prepare($conn, $sql_post);
mysqli_stmt_bind_param($stmt_p, "i", $id_post);
mysqli_stmt_execute($stmt_p);
$post = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_p));

// Validación de existencia: Si el ID en la URL es alterado o no existe en la BD.
if (!$post) {
    die("El post no existe.");
}

// 3. RECUPERACIÓN DE COMENTARIOS ASOCIADOS
// Obtenemos todos los registros de la tabla 'comentarios' vinculados al ID del post.
$sql_comm = "SELECT c.*, u.nombre FROM comentarios c JOIN usuarios u ON c.id_usuario = u.id_usuario WHERE c.id_post = ? ORDER BY c.fecha_comentario ASC";
$stmt_c = mysqli_prepare($conn, $sql_comm);
mysqli_stmt_bind_param($stmt_c, "i", $id_post);
mysqli_stmt_execute($stmt_c);
$comentarios = mysqli_stmt_get_result($stmt_c);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($post['titulo']); ?> | RetroGaming Hub</title>
  <link rel="stylesheet" href="style.css">
</head>

<body class="site-wrapper"> 
  <header class="site-header">
    <div class="container header-inner">
      <a href="index.php" class="brand">
        <div class="brand-badge">RGH</div>
        <div>
          <div class="brand-name">RetroGaming Hub</div>
          <div class="brand-tagline">Comunidad Retro</div>
        </div>
      </a>

      <nav class="nav">
        <a class="nav-link" href="index.php">Inicio</a>
        <a class="nav-link" href="feed.php">Feed Global</a>
        <?php if ($loggedIn): ?>
          <a class="nav-link" href="perfil.php">Mi Perfil</a>
        <?php endif; ?>
      </nav>

      <div class="header-actions">
        <?php if ($loggedIn): ?>
          <a class="btn btn-ghost" href="nuevo_post.php">Crear post</a>
          <a class="btn btn-primary" href="../backend/logout.php">Salir</a>
        <?php else: ?>
          <a class="btn btn-primary" href="login.php">Iniciar sesión</a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <main id="contenido" class="main-content section"> 
    <div class="container thread-layout">

      <article class="thread-post card">
        <div class="post-top">
          <span class="badge"><?php echo htmlspecialchars($post['categoria']); ?></span>
          <span class="meta-text">por <strong><?php echo htmlspecialchars($post['nombre']); ?></strong> • <?php echo date('d/m/Y', strtotime($post['fecha_creacion'])); ?></span>
        </div>
        <h1 class="thread-title"><?php echo htmlspecialchars($post['titulo']); ?></h1>
        <p class="thread-body"><?php echo nl2br(htmlspecialchars($post['contenido'])); ?></p>
      </article>

      <section class="thread-comments">
        <div class="comments-head">
          <h2>Comentarios</h2>
        </div>

        <?php if ($loggedIn): ?>
          <form class="comment-form card" method="post" action="../backend/add_comment.php">
            <input type="hidden" name="id_post" value="<?php echo $id_post; ?>">
            <label class="control-label">Añadir comentario</label>
            <textarea class="control-input textarea" name="contenido" rows="4" placeholder="Escribe tu comentario…" required></textarea>
            <div class="comment-actions">
              <button class="btn btn-primary" type="submit">Publicar comentario</button>
            </div>
          </form>
        <?php else: ?>
          <div class="card" style="text-align: center; padding: 30px;">
            <p class="meta-text">Necesitas iniciar sesión para comentar.</p>
            <a class="btn btn-primary" href="login.php" style="margin-top: 10px;">Iniciar sesión ahora</a>
          </div>
        <?php endif; ?>

        <div class="comment-list">
          <?php if (mysqli_num_rows($comentarios) > 0): ?>
            <?php while($com = mysqli_fetch_assoc($comentarios)): ?>
              <article class="comment card">
                <div class="comment-top" style="justify-content: flex-end;"> 
                  <span class="meta-text">
                    <strong><?php echo htmlspecialchars($com['nombre']); ?></strong> • 
                    <?php echo date('d/m H:i', strtotime($com['fecha_comentario'])); ?>
                  </span>
                </div>
                <p class="comment-body"><?php echo nl2br(htmlspecialchars($com['contenido'])); ?></p>
              </article>
            <?php endwhile; ?>
          <?php else: ?>
            <p class="meta-text" style="text-align: center; margin-top: 20px;">No hay comentarios aún. ¡Sé el primero!</p>
          <?php endif; ?>
        </div>
      </section>

    </div>
  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
        <div class="footer-info-block">
            <div class="brand-name" style="color: var(--accent); font-size: 0.9rem; margin-bottom: 5px;">RETROGAMING HUB</div>
            <p class="meta-text" style="font-size: 0.75rem; max-width: 250px;">Plataforma de preservación y discusión sobre sistemas de entretenimiento vintage.</p>
        </div>
        <div class="footer-info-block" style="text-align: center;">
            <div class="status-indicator">
                <span class="status-dot"></span>
                <span class="meta-text" style="font-size: 0.7rem; font-family: var(--mono);">SYSTEM_STATUS: ONLINE</span>
            </div>
            <p class="meta-text" style="font-size: 0.7rem; opacity: 0.5; margin-top: 5px;">XAMPP SERVER • PHP 8.2 • MYSQL</p>
        </div>
        <div class="footer-info-block" style="text-align: right;">
            <div class="badge" style="font-size: 0.65rem; letter-spacing: 1px;">BUILD 2026.01.10</div>
            <p class="meta-text" style="font-size: 0.7rem; margin-top: 5px;">© UT3 Foro Web Project</p>
        </div>
    </div>
  </footer>
</body>
</html>