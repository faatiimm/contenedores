<?php 
/**
 * GESTIÓN DE ERRORES POR URL
 * Se capturan los códigos de error enviados desde el backend (register.php)
 * para mostrar mensajes amigables al usuario final sin recargar la lógica completa.
 */
if (isset($_GET['error'])): ?>
    <div class="auth2-error" style="background: rgba(255,107,139,0.1); border: 1px solid var(--danger); padding: 12px; border-radius: 12px; margin-bottom: 15px;">
        <?php 
            // Control específico para evitar duplicidad de cuentas en la base de datos
            if ($_GET['error'] == "email_exists") {
                echo "⚠️ Este correo electrónico ya está registrado. Intenta iniciar sesión.";
            } else {
                echo "⚠️ Hubo un problema con el registro. Inténtalo de nuevo.";
            }
        ?>
    </div>
<?php endif; ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registrarse | RetroGaming Hub</title>
  <link rel="stylesheet" href="style.css">
</head>

<body class="auth2-body">
  <a class="skip-link" href="#contenido">Saltar al contenido</a>

  <header class="site-header">
    <div class="container header-inner">
      <div class="brand">
        <div class="brand-badge" aria-hidden="true">RGH</div>
        <div>
          <div class="brand-name">RetroGaming Hub</div>
          <div class="brand-tagline">Foro de coleccionistas y amantes del retro</div>
        </div>
      </div>

      <div class="header-actions">
        <a class="btn btn-ghost" href="index.php">Volver</a>
        <a class="btn btn-ghost" href="login.php">Iniciar sesión</a>
      </div>
    </div>
  </header>

  <main id="contenido" class="auth2-main">
    <section class="auth2-grid">
      <div class="auth2-left">
        <p class="auth2-kicker">Crear una cuenta</p>
        <h1 class="auth2-title">Registrarse</h1>
        <p class="auth2-subtitle">
          Únete para publicar en el foro, comentar en hilos y guardar tus plataformas favoritas.
        </p>

        <ul class="auth2-bullets">
          <li>Acceso a posts y comentarios.</li>
          <li>Perfil con cambio de contraseña.</li>
          <li>Filtrado por plataforma y colecciones.</li>
        </ul>
      </div>

      <div class="auth2-card">
        <div class="auth2-card-head">
          <div class="auth2-logo" aria-hidden="true">RGH</div>
          <div>
            <div class="auth2-card-title">Bienvenido</div>
            <div class="auth2-card-desc">Rellena tus datos para crear tu cuenta.</div>
          </div>
        </div>

        <form method="POST" action="../backend/register.php"  class="auth2-form">
          <div class="auth2-field">
            <label class="auth2-label" for="usuario">Usuario</label>
            <input class="auth2-input" type="text" id="usuario" name="usuario" placeholder="Nombre de cuenta" required />
          </div>

          <div class="auth2-field">
            <label class="auth2-label" for="email">Email</label>
            <input class="auth2-input" type="email" id="email" name="email" placeholder="tuemail@ejemplo.com" required />
          </div>

          <div class="auth2-field">
            <label class="auth2-label" for="password">Contraseña</label>
            <input class="auth2-input" type="password" id="password" name="password" placeholder="Mínimo 8 caracteres" required />
          </div>

          <div class="auth2-field">
            <label class="auth2-label" for="password2" >Repetir contraseña</label>
            <input class="auth2-input" type="password" id="password2" name="password2"  placeholder="Repite la contraseña" required />
          </div>

          <label class="auth2-check auth2-terms">
            <input type="checkbox" name="terminos" required />
            <span>Acepto los términos y condiciones</span>
          </label>

          <?php if (isset($_GET["error"])): ?>
            <p class="auth2-error">No se ha podido crear la cuenta.</p>
          <?php endif; ?>

          <button class="auth2-btn" type="submit">Crear cuenta</button>

          <p class="auth2-foot">
            ¿Ya tienes cuenta?
            <a class="auth2-link" href="login.php">Iniciar sesión</a>
          </p>
        </form>
      </div>
    </section>
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