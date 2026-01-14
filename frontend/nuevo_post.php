<?php
/**
 * GESTIÓN DE SESIÓN
 * Se inicia la sesión para verificar si el usuario tiene permisos de publicación.
 */
session_start();

/**
 * VALIDACIÓN DE IDENTIDAD
 * Se comprueba si existe la clave de usuario en la superglobal $_SESSION.
 * Esta variable determinará si se muestra el formulario o un aviso de error.
 */
$loggedIn = isset($_SESSION["id_usuario"]);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Nuevo post | RetroGaming Hub</title>
  <link rel="stylesheet" href="style.css">
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
      <a class="nav-link" href="feed.php">Feed Global</a>
      <?php if (isset($_SESSION["id_usuario"])): ?>
        <a class="nav-link" href="perfil.php">Mi Perfil</a>
      <?php endif; ?>
    </nav>

    <div class="header-actions">
      <?php if (isset($_SESSION["id_usuario"])): ?>
        <a class="btn btn-ghost" href="nuevo_post.php">Crear post</a>
        <a class="btn btn-primary" href="../backend/logout.php">Salir</a>
      <?php else: ?>
        <a class="btn btn-primary" href="login.php">Iniciar sesión</a>
      <?php endif; ?>
    </div>
  </div>
</header>

  <main id="contenido" class="section">
    <div class="container">
      <div class="section-head">
        <h1>Crear nuevo post</h1>
      </div>

      <?php if (!$loggedIn): ?>
        <div class="card">
          <p class="meta-text">Necesitas iniciar sesión para publicar.</p>
          <a class="btn btn-primary" href="login.php">Iniciar sesión</a>
        </div>
      <?php else: ?>
        
        <form class="card post-form" method="post" action="../backend/add_post.php">
          <div class="form-grid">
            
            <div class="control">
              <label class="control-label" for="titulo">Título</label>
              <input class="control-input" id="titulo" name="titulo" type="text" placeholder="Ej: Restauración de mi Dreamcast" required />
            </div>

            <div class="control">
              <label class="control-label" for="plataforma">Plataforma</label>
              <select class="control-input select-custom" id="plataforma" name="categoria" required>
                <option value="" disabled selected hidden>Selecciona una temática:</option>
                <option value="Nintendo">Nintendo</option>
                <option value="SEGA">SEGA</option>
                <option value="PlayStation">PlayStation</option>
                <option value="Arcade">Arcade</option>
                <option value="PC Retro">PC Retro</option>
              </select>
            </div>
          </div>

          <div class="control">
            <label class="control-label" for="contenido_post">Contenido</label>
            <textarea class="control-input textarea" id="contenido_post" name="contenido" rows="10" placeholder="Escribe aquí tu publicación…" required></textarea>
          </div>

          <div class="comment-actions">
            <button class="btn btn-primary" type="submit">Publicar</button>
            <a class="btn btn-ghost" href="feed.php">Cancelar</a>
          </div>
        </form>
      <?php endif; ?>
    </div>
  </main>

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