<?php
/**
 * ARCHIVO: frontend/login.php
 * FINALIDAD: Interfaz de usuario para la autenticación en el sistema.
 * CUMPLE REQUISITO: "Desde la página principal se debe poder acceder al inicio de sesión".
 */

session_start();

/**
 * GESTIÓN DE NOTIFICACIONES DE ERROR
 * Captura parámetros enviados por el backend mediante el método GET para 
 * informar al usuario sobre el resultado de su intento de acceso.
 */
$errorType = isset($_GET['error']) ? $_GET['error'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión | RetroGaming Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth2-body">
    <header class="site-header">
        <div class="container header-inner">
            <div class="brand">
                <div class="brand-badge">RGH</div>
                <div>
                    <div class="brand-name">RetroGaming Hub</div>
                    <div class="brand-tagline">Foro retro</div>
                </div>
            </div>
        </div>
    </header>

    <main id="contenido" class="auth2-main">
        <section class="auth2-grid">
            <div class="auth2-left">
                <span class="auth2-kicker">WELCOME</span>
                <h1 class="auth2-title">Vuelve al juego</h1>
                <p class="auth2-subtitle">Accede a la comunidad retro y participa en el foro.</p>
            </div>

            <div class="auth2-card">
                <div class="auth2-card-head">
                    <div class="auth2-logo">RGH</div>
                    <div>
                        <div class="auth2-card-title">Iniciar sesión</div>
                        <div class="auth2-card-desc">Accede con tu cuenta</div>
                    </div>
                </div>

                <form action="../backend/login.php" method="POST" class="auth2-form">
                    <div class="auth2-field">
                        <label class="auth2-label">Email</label>
                        <input class="auth2-input" type="text" name="email" required>
                    </div>

                    <div class="auth2-field">
                        <label class="auth2-label">Contraseña</label>
                        <input class="auth2-input" type="password" name="password" required>
                    </div>

                    <button class="auth2-btn" type="submit">Entrar</button>

                    <?php if ($errorType === "credenciales"): ?>
                        <p class="auth2-error" style="color: #ff4d4d; margin-top: 10px; font-size: 0.85rem; background: rgba(255, 77, 77, 0.1); padding: 8px; border-radius: 4px; text-align: center;">
                            ⚠️ La contraseña es incorrecta.
                        </p>
                    <?php elseif ($errorType === "no_existe"): ?>
                        <p class="auth2-error" style="color: #ff4d4d; margin-top: 10px; font-size: 0.85rem; background: rgba(255, 77, 77, 0.1); padding: 8px; border-radius: 4px; text-align: center;">
                            ⚠️ El usuario o correo no está registrado.
                        </p>
                    <?php elseif ($errorType === "campos"): ?>
                        <p class="auth2-error" style="color: #ff4d4d; margin-top: 10px; font-size: 0.85rem; background: rgba(255, 77, 77, 0.1); padding: 8px; border-radius: 4px; text-align: center;">
                            ⚠️ Por favor, rellena todos los campos.
                        </p>
                    <?php endif; ?>

                    <p style="margin-top: 15px;">
                        ¿No tienes cuenta? <a class="auth2-link" href="registro.php">Regístrate</a>
                    </p>
                </form>
            </div>
        </section>
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