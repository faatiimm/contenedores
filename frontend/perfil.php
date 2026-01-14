<?php
/**
 * ARCHIVO: frontend/perfil.php
 * FINALIDAD: Área privada del usuario para gestión de cuenta y visualización de actividad.
 * REQUISITO: "El perfil debe permitir al usuario ver sus datos y modificar la contraseña".
 */

session_start();
require_once "../database/connection.php";

/**
 * CONTROL DE ACCESO (SEGURIDAD)
 * Si no existe una sesión activa, se redirige al login para proteger los datos privados.
 */
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];

/**
 * RECUPERACIÓN DE DATOS DE USUARIO
 * Consulta preparada para obtener la información actualizada del perfil.
 */
$sql_user = "SELECT * FROM usuarios WHERE id_usuario = ?";
$stmt = mysqli_prepare($conn, $sql_user);
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$usuario_db = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

/**
 * ESTADÍSTICAS EN TIEMPO REAL
 * Conteo de registros vinculados al ID del usuario en las tablas 'posts' y 'comentarios'.
 */
// Conteo de posts
$sql_count_posts = "SELECT COUNT(*) as total FROM posts WHERE id_usuario = ?";
$stmt_p = mysqli_prepare($conn, $sql_count_posts);
mysqli_stmt_bind_param($stmt_p, "i", $id_usuario);
mysqli_stmt_execute($stmt_p);
$total_posts = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_p))['total'];

// Conteo de comentarios
$sql_count_comm = "SELECT COUNT(*) as total FROM comentarios WHERE id_usuario = ?";
$stmt_c = mysqli_prepare($conn, $sql_count_comm);
mysqli_stmt_bind_param($stmt_c, "i", $id_usuario);
mysqli_stmt_execute($stmt_c);
$total_comentarios = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_c))['total'];

/**
 * LISTADO DE ACTIVIDAD PROPIA
 * Se recuperan los hilos creados por el usuario ordenados por fecha descendente.
 */
$sql_mis_posts = "SELECT * FROM posts WHERE id_usuario = ? ORDER BY fecha_creacion DESC";
$stmt_m = mysqli_prepare($conn, $sql_mis_posts);
mysqli_stmt_bind_param($stmt_m, "i", $id_usuario);
mysqli_stmt_execute($stmt_m);
$mis_posts_res = mysqli_stmt_get_result($stmt_m);

// Gestión de feedback visual mediante parámetros URL
$ok = $_GET['ok'] ?? null;
$err = $_GET['err'] ?? null;
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Perfil | RetroGaming Hub</title>
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

  <?php if ($ok === "pass"): ?>
    <div class="toast toast-ok">
      <div class="toast-title">¡Éxito!</div>
      <div class="toast-desc">Contraseña actualizada correctamente.</div>
    </div>
  <?php endif; ?>

  <main id="contenido" class="section">
    <div class="container">

      <div class="profile-head">
        <h1 class="profile-title">Mi Cuenta</h1>
      </div>

      <div class="profile-grid">
        <section class="card profile-card">
            <div class="profile-header">
                <?php if (isset($_GET['edit'])): ?>
                    <form action="../backend/update_profile.php" method="POST" style="width: 100%;">
                        <div class="control">
                            <label class="control-label">Nuevo Nombre de Usuario</label>
                            <input type="text" name="nuevo_nombre" class="control-input" 
                                   value="<?php echo htmlspecialchars($_SESSION['nombre']); ?>" required>
                        </div>
                        <div class="comment-actions" style="margin-top: 15px;">
                            <button type="submit" class="btn btn-primary btn-small">Guardar</button>
                            <a href="perfil.php" class="btn btn-ghost btn-small">Cancelar</a>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="profile-info">
                        <h2 class="profile-name" style="font-size: 1.8rem; margin-bottom: 5px;">
                            <?php echo htmlspecialchars($_SESSION['nombre']); ?>
                        </h2>
                        <p class="profile-email" style="opacity: 0.7; margin-bottom: 15px;">
                            <?php echo htmlspecialchars($_SESSION['email']); ?>
                        </p>
                        <a href="perfil.php?edit=true" class="btn btn-ghost btn-small" style="border-color: var(--accent);">
                            ✏️ Editar Perfil
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="divider" style="margin: 20px 0; border-top: 1px solid var(--line);"></div>

            <div class="profile-stats" style="display: flex; gap: 30px;">
                <div><strong><?php echo $total_posts; ?></strong> <span class="meta-text">posts</span></div>
                <div><strong><?php echo $total_comentarios; ?></strong> <span class="meta-text">comentarios</span></div>
            </div>
        </section>  

        <section class="card profile-card" style="margin-top: 20px;">
            <h2 class="card-title">Seguridad de la cuenta</h2>

            <?php if (isset($_GET['cambiar_pass'])): ?>
                <p class="meta-text" style="margin-bottom: 20px;">Ingresa tu contraseña actual y la nueva para actualizar tus credenciales.</p>
                
                <form action="../backend/change_password.php" method="POST" class="auth2-form">
                    <div class="auth2-field">
                        <label class="auth2-label">Contraseña Actual</label>
                        <input type="password" name="pass_actual" class="auth2-input" placeholder="••••••••" required>
                    </div>

                    <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="auth2-field">
                            <label class="auth2-label">Nueva Contraseña</label>
                            <input type="password" name="pass_nueva" class="auth2-input" placeholder="Mínimo 8 caracteres" required>
                        </div>
                        <div class="auth2-field">
                            <label class="auth2-label">Repetir Nueva</label>
                            <input type="password" name="pass_confirmar" class="auth2-input" placeholder="Repite la contraseña" required>
                        </div>
                    </div>

                    <div class="comment-actions" style="margin-top: 20px; display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
                        <a href="perfil.php" class="btn btn-ghost">Cancelar</a>
                    </div>
                </form>

            <?php else: ?>
                <p class="meta-text">¿Quieres actualizar tu clave de acceso? Por seguridad, te pediremos tu contraseña actual.</p>
                <div style="margin-top: 15px;">
                    <a href="perfil.php?cambiar_pass=1" class="btn btn-ghost" style="border-color: var(--accent2); color: var(--accent2);">
                        🔑 Cambiar Contraseña
                    </a>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error_pass'])): ?>
                <p style="color: var(--danger); font-size: 0.8rem; margin-top: 10px;">⚠️ La contraseña actual es incorrecta o las nuevas no coinciden.</p>
            <?php endif; ?>
        </section>
      </div>

      <section class="card profile-card" style="margin-top: 20px;">
          <div class="card-header" style="margin-bottom: 20px;">
              <h2 class="card-title">Mis publicaciones</h2>
              <p class="meta-text">Gestiona los hilos que has compartido en la comunidad.</p>
          </div>

          <?php if (mysqli_num_rows($mis_posts_res) > 0): ?>
              <div class="feed-grid" style="display: grid; gap: 15px; margin-top: 15px;">
                  
                  <?php while($post = mysqli_fetch_assoc($mis_posts_res)): ?>
                      <article class="post-card" style="background: rgba(255,255,255,0.03); border: 1px solid var(--line); border-radius: 12px; padding: 15px;">
                          <div class="post-top" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                              <span class="badge" style="font-size: 0.7rem;"><?php echo htmlspecialchars($post['categoria']); ?></span>
                              <span class="meta-text" style="font-size: 0.75rem;"><?php echo date('d/m/Y', strtotime($post['fecha_creacion'])); ?></span>
                          </div>

                          <h3 class="post-title" style="font-size: 1.1rem; margin-bottom: 15px;">
                              <?php echo htmlspecialchars($post['titulo']); ?>
                          </h3>

                          <div class="post-footer" style="display: flex; gap: 10px;">
                              <a class="btn btn-small btn-ghost" href="thread.php?id=<?php echo $post['id_post']; ?>" style="font-size: 0.8rem;">Ver hilo</a>
                              <a class="btn btn-small btn-ghost" 
                                style="color:var(--danger); border-color: rgba(255,71,87,0.3);" 
                                href="../backend/delete_post.php?id=<?php echo $post['id_post']; ?>"
                                onclick="return confirm('¿Estás seguro de que quieres borrar este hilo?')">
                                Borrar
                              </a>
                          </div>
                      </article>
                  <?php endwhile; ?>

              </div>
          <?php else: ?>
              <div style="text-align: center; padding: 40px 20px;">
                  <p class="meta-text">Aún no has publicado nada. ¡Comparte algo con la comunidad!</p>
                  <a href="nuevo_post.php" class="btn btn-primary btn-small" style="margin-top: 15px;">Crear mi primer post</a>
              </div>
          <?php endif; ?>
      </section>
    </div>
  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
        <div class="footer-info-block">
            <div class="brand-name" style="color: var(--accent); font-size: 0.9rem; margin-bottom: 5px;">RETROGAMING HUB</div>
            <p class="meta-text" style="font-size: 0.75rem; max-width: 250px;">Plataforma de preservación y discusión sobre sistemas vintage.</p>
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