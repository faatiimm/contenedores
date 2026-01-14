<?php
/**
 * INICIO DE SESIÓN Y PERSISTENCIA
 * Se arranca el motor de sesiones para verificar si el visitante tiene una identidad activa.
 */
session_start();

/**
 * CONTROL DE ESTADO DE AUTENTICACIÓN
 * Variable booleana que determina si el usuario ha superado el login. 
 * Se utiliza para renderizar componentes dinámicos en la interfaz.
 */
$loggedIn = isset($_SESSION["id_usuario"]);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>RetroGaming Hub</title>
  <link rel="stylesheet" href="style.css">
</head>

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
      <a class="nav-link" href="feed.php">Explorar</a>
    </nav>

    <div class="header-actions">
      <?php if (isset($_SESSION["id_usuario"])): ?>
      <div class="user-pill">
        <a href="perfil.php" class="user-info">
            <span class="user-avatar">
                <?php echo strtoupper(substr($_SESSION['nombre'], 0, 1)); ?>
            </span>
            <span class="user-name">
                <?php echo htmlspecialchars($_SESSION['nombre']); ?>
            </span>
        </a>
      </div>
        <a class="btn btn-primary btn-small" href="nuevo_post.php">Crear</a>
      <?php else: ?>
        <a class="btn btn-ghost" href="login.php">Iniciar sesión</a>
        <a class="btn btn-primary" href="registro.php">Únete</a>
      <?php endif; ?>
    </div>

  </div>
</header>

<body>
<section class="hero-gradient">
  <div class="container hero-center">
    <div class="hero-icon" aria-hidden="true">🕹️</div>
    
    <h1 class="hero-title hero-title-center">Bienvenido a RetroGaming Hub</h1>
    <p class="hero-subtitle hero-subtitle-center">
      La comunidad definitiva para los amantes de las consolas clásicas y la cultura del videojuego vintage.
    </p>

    <div class="hero-ctas hero-ctas-center">
      <?php if ($loggedIn): ?>
        <a class="btn btn-invert btn-primary" href="feed.php">Ir al Feed Global</a>
        <a class="btn btn-invert btn-ghost" href="perfil.php">Ver mi Perfil</a>
      <?php else: ?>
        <a class="btn btn-invert btn-primary" href="login.php">Comenzar ahora</a>
        <a class="btn btn-invert btn-ghost" href="registro.php">Crear cuenta</a>
      <?php endif; ?>
    </div>
  </div>
</section>

    <section class="section section-light" id="que-es">
      <div class="container">
        <div class="section-head section-head-center">
          <h2>¿Qué es RetroGaming Hub?</h2>
          <p>Un espacio para publicar, descubrir y participar en la comunidad retro.</p>
        </div>

        <div class="features">
          <article class="feature">
            <div class="feature-icon feature-icon-chat" aria-hidden="true">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                <path d="M21 12a8 8 0 0 1-8 8H7l-4 3 1-5a8 8 0 1 1 17-6Z"
                  stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              </svg>
            </div>
            <h3>Comparte</h3>
            <p>Publica tus experiencias, trucos y descubrimientos sobre tus juegos favoritos.</p>
          </article>

          <article class="feature">
            <div class="feature-icon feature-icon-pad" aria-hidden="true">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                <path d="M7 15h2m-1-1v2M15 14h.01M17 13h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M6 10h12c2 0 4 2 4 5a4 4 0 0 1-7 2l-1-1H10l-1 1a4 4 0 0 1-7-2c0-3 2-5 4-5Z"
                  stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              </svg>
            </div>
            <h3>Descubre</h3>
            <p>Encuentra nuevos juegos retro y conecta con una comunidad apasionada.</p>
          </article>

          <article class="feature">
            <div class="feature-icon feature-icon-trophy" aria-hidden="true">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                <path d="M8 21h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M12 17v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M7 4h10v4a5 5 0 0 1-10 0V4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                <path d="M7 6H4a2 2 0 0 0 2 4h1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M17 6h3a2 2 0 0 1-2 4h-1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </div>
            <h3>Compite</h3>
            <p>Participa en debates, comparte logros y forma parte de la comunidad.</p>
          </article>
        </div>
      </div>
    </section>

<section class="section section-light" id="plataformas">
  <div class="container">
    <div class="section-head section-head-center">
      <h2>Plataformas</h2>
    </div>

    <div class="platforms">
      <a class="platform-card platform-nintendo" href="feed.php?tematica=Nintendo">
        <div class="platform-top">
          <h3>Nintendo</h3>
        </div>
        <div class="platform-bottom">
          <p>Switch, Wii, GameCube y más</p>
        </div>
      </a>

      <a class="platform-card platform-sega" href="feed.php?tematica=SEGA">
        <div class="platform-top">
          <h3>SEGA</h3>
        </div>
        <div class="platform-bottom">
          <p>Genesis, Dreamcast, Saturn</p>
        </div>
      </a>

      <a class="platform-card platform-ps" href="feed.php?tematica=PlayStation">
        <div class="platform-top">
          <h3>PlayStation</h3>
        </div>
        <div class="platform-bottom">
          <p>PS5, PS4, PS3 y clásicas</p>
        </div>
      </a>
    </div>

  </div>
</section>

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