<?php
/**
 * ARCHIVO: frontend/feed.php
 * FINALIDAD: Visualización de publicaciones con estado inicial de carga total y opción de reset.
 */

session_start();
require_once "../database/connection.php";

// Verificación de sesión
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

/**
 * 1. GESTIÓN DE FILTROS
 * Si no hay temática, el sistema entiende que debe mostrar todo.
 */
$tematica = $_GET['tematica'] ?? null; 
$orden = $_GET['orden'] ?? 'recientes';

/**
 * 2. CONSTRUCCIÓN DE CONSULTA SQL
 * Se extrae el nombre del autor y el conteo de comentarios en una sola operación.
 */
$sql = "SELECT p.*, u.nombre as autor, 
        (SELECT COUNT(*) FROM comentarios c WHERE c.id_post = p.id_post) as total_comentarios
        FROM posts p 
        INNER JOIN usuarios u ON p.id_usuario = u.id_usuario";

// Lógica de filtrado condicional
if ($tematica && $tematica !== '') {
    $sql .= " WHERE p.categoria = '" . mysqli_real_escape_string($conn, $tematica) . "'";
}

$sql .= ($orden === 'antiguos') ? " ORDER BY p.fecha_creacion ASC" : " ORDER BY p.fecha_creacion DESC";

$resultado = mysqli_query($conn, $sql);
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Feed | RetroGaming Hub</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* Estilos para asegurar la visibilidad de los selects */
    .control-input {
        background-color: #1a1d29 !important;
        color: #ffffff !important;
        border: 1px solid var(--line);
    }
    .control-input option {
        background-color: #12162a;
        color: #ffffff;
    }
    /* Contenedor para alinear los controles y el botón de reset */
    .feed-controls-wrapper {
        display: flex;
        align-items: flex-end;
        gap: 15px;
        flex-wrap: wrap;
    }
  </style>
</head>
<body>

<header class="site-header">
  <div class="container header-inner">
    <a href="index.php" class="brand">
      <div class="brand-badge" aria-hidden="true">RGH</div>
      <div>
        <div class="brand-name">RetroGaming Hub</div>
        <div class="brand-tagline">Comunidad Retro</div>
      </div>
    </a>

    <nav class="nav">
      <a class="nav-link" href="index.php">Inicio</a>
      <a class="nav-link active" href="feed.php">Feed Global</a>
      <?php if (isset($_SESSION["id_usuario"])): ?>
        <a class="nav-link" href="perfil.php">Mi Perfil</a>
      <?php endif; ?>
    </nav>

    <div class="header-actions">
      <a class="btn btn-ghost" href="nuevo_post.php">Crear post</a>
      <a class="btn btn-primary" href="../backend/logout.php">Salir</a>
    </div>
  </div>
</header>

<main class="section container">
    
    <section class="feed-toolbar card">
      <form method="GET" action="feed.php" class="feed-controls-wrapper">
        
        <div class="control" style="flex: 1; min-width: 200px;">
          <label class="control-label">Filtrar por Sistema</label>
          <select name="tematica" class="control-input" onchange="this.form.submit()">
            <option value="" <?php echo !$tematica ? 'selected' : ''; ?>>Selecciona un sistema...</option>
            <option value="Nintendo" <?php echo ($tematica == 'Nintendo') ? 'selected' : ''; ?>>Nintendo</option>
            <option value="SEGA" <?php echo ($tematica == 'SEGA') ? 'selected' : ''; ?>>SEGA</option>
            <option value="PlayStation" <?php echo ($tematica == 'PlayStation') ? 'selected' : ''; ?>>PlayStation</option>
            <option value="Arcade" <?php echo ($tematica == 'Arcade') ? 'selected' : ''; ?>>Arcade</option>
          </select>
        </div>

        <div class="control" style="flex: 1; min-width: 200px;">
          <label class="control-label">Orden</label>
          <select name="orden" class="control-input" onchange="this.form.submit()">
            <option value="recientes" <?php echo ($orden == 'recientes') ? 'selected' : ''; ?>>Más recientes</option>
            <option value="antiguos" <?php echo ($orden == 'antiguos') ? 'selected' : ''; ?>>Más antiguos</option>
          </select>
        </div>

        <?php if ($tematica): ?>
        <div class="control">
          <a href="feed.php" class="btn btn-ghost" style="border-color: var(--danger); color: var(--danger);">
             ✕ Limpiar Filtros
          </a>
        </div>
        <?php endif; ?>

      </form>
    </section>

    <div class="feed-grid">
      <?php if (mysqli_num_rows($resultado) > 0): ?>
        <?php while($post = mysqli_fetch_assoc($resultado)): ?>
          <article class="post-card">
            <div class="post-top">
              <span class="badge"><?php echo htmlspecialchars($post['categoria']); ?></span>
              <span class="meta-text"><?php echo date('d/m/Y', strtotime($post['fecha_creacion'])); ?></span>
            </div>
            
            <h2 class="post-title"><?php echo htmlspecialchars($post['titulo']); ?></h2>
            <p class="post-excerpt"><?php echo htmlspecialchars(substr($post['contenido'], 0, 120)); ?>...</p>
            
            <div class="post-stats">
              <span>👤 <?php echo htmlspecialchars($post['autor']); ?></span>
              <span>💬 <?php echo $post['total_comentarios']; ?> comentarios</span>
            </div>

            <div class="post-footer">
              <a href="thread.php?id=<?php echo $post['id_post']; ?>" class="btn btn-small btn-ghost" style="width:100%">Ver conversación</a>
            </div>
          </article>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="meta-text">No hay publicaciones para mostrar.</p>
      <?php endif; ?>
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
        </div>
        <div class="footer-info-block" style="text-align: right;">
            <div class="badge" style="font-size: 0.65rem; letter-spacing: 1px;">BUILD 2026.01.10</div>
            <p class="meta-text" style="font-size: 0.7rem; margin-top: 5px;">© UT3 Foro Web Project</p>
        </div>
    </div>
</footer>

</body>
</html>