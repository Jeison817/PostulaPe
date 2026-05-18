<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['IdPerfil'] != 1) {
    header("Location: login.php?error=permiso");
    exit;
}

$user = $_SESSION['user'];
$nombreCompleto = trim(($user['Nombre'] ?? '') . ' ' . ($user['ApellidoPaterno'] ?? '') . ' ' . ($user['ApellidoMaterno'] ?? ''));

if (empty($nombreCompleto)) {
    $nombreCompleto = "Usuario no identificado";
}

// Opcionalmente capturamos mensajes de error o éxito pasados por sesión desde el controlador
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Crear Nuevo Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        /* Tus estilos personalizados */
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #ecf0f1;
        }
        main {
            flex: 1;
            padding-bottom: 80px;
        }
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1030;
        }
        .navbar-custom, .footer-custom {
            background-color: #34495e !important;
        }
        .form-card {
            max-width: 700px;
            margin: 40px auto;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
            background: #fff;
        }
        .form-card h2 {
            font-size: 1.6rem;
            font-weight: 600;
            color: #2c3e50;
        }
        .form-label {
            font-weight: 500;
            font-size: 0.95rem;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .form-control::placeholder {
            color: #aaa;
            font-style: italic;
        }
        .form-control-sm {
            font-size: 0.9rem;
            padding: 0.4rem 0.6rem;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
      <i class="bi bi-person-fill me-2"></i>
      Sistema de Reclutamiento
      <span class="ms-2 fw-normal fs-6">/ <?= htmlspecialchars($nombreCompleto) ?></span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>

<main class="container">
  <div class="card form-card p-4">
    <h2 class="mb-4 text-center">Nuevo Usuario</h2>

    <?php if ($error) : ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success) : ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form action="index.php?controller=usuario&action=guardar" method="post" autocomplete="off">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="Nombre" class="form-label">Nombre</label>
          <input type="text" name="Nombre" id="Nombre" class="form-control form-control-sm" required placeholder="Ej: Juan" value="<?= htmlspecialchars($_POST['Nombre'] ?? '') ?>">
        </div>

        <div class="col-md-6 mb-3">
          <label for="ApellidoPaterno" class="form-label">Apellido Paterno</label>
          <input type="text" name="ApellidoPaterno" id="ApellidoPaterno" class="form-control form-control-sm" required placeholder="Ej: Pérez" value="<?= htmlspecialchars($_POST['ApellidoPaterno'] ?? '') ?>">
        </div>

        <div class="col-md-6 mb-3">
          <label for="ApellidoMaterno" class="form-label">Apellido Materno</label>
          <input type="text" name="ApellidoMaterno" id="ApellidoMaterno" class="form-control form-control-sm" required placeholder="Ej: Rodríguez" value="<?= htmlspecialchars($_POST['ApellidoMaterno'] ?? '') ?>">
        </div>

        <!--div class="col-md-6 mb-3">
          <label for="Usuario" class="form-label">Nombre de Usuario</label>
          <input type="text" name="Usuario" id="Usuario" class="form-control form-control-sm" required placeholder="Ej: juanperez123" value="<?= htmlspecialchars($_POST['Usuario'] ?? '') ?>">
        </div-->
        <div class="col-md-6 mb-3">
  <label for="UsuarioBase" class="form-label">Nombre de Usuario</label>
  <div class="input-group">
    <input type="text" name="UsuarioBase" id="UsuarioBase" class="form-control form-control-sm" required placeholder="Ej: juanperez123" value="<?= htmlspecialchars($_POST['UsuarioBase'] ?? '') ?>">
    <span class="input-group-text">@system.com</span>
  </div>
</div>

        <div class="col-md-6 mb-3">
          <label for="Contrasena" class="form-label">Contraseña</label>
          <input type="password" name="Contrasena" id="Contrasena" class="form-control form-control-sm" required placeholder="Mínimo 6 caracteres" autocomplete="new-password">
        </div>

        <div class="col-md-6 mb-3">
          <label for="IdPerfil" class="form-label">Perfil</label>
          <select name="IdPerfil" id="IdPerfil" class="form-select form-select-sm" required>
            <option value="">Seleccione un perfil</option>
            <option value="1" <?= (isset($_POST['IdPerfil']) && $_POST['IdPerfil'] == 1) ? 'selected' : '' ?>>Administrador</option>
            <option value="3" <?= (isset($_POST['IdPerfil']) && $_POST['IdPerfil'] == 3) ? 'selected' : '' ?>>Evaluador</option>
          </select>
        </div>
      </div>

      <div class="d-flex justify-content-between mt-4">
        <a href="index.php?controller=usuario&action=index" class="btn btn-secondary btn-sm">
          <i class="bi bi-arrow-left"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-primary btn-sm">
          <i class="bi bi-save me-1"></i> Guardar
        </button>
      </div>
    </form>
  </div>
</main>

<footer class="footer-custom text-light text-center text-lg-start">
  <div class="container p-3">
    <div class="row">
      <div class="col-lg-6 col-md-12 mb-2 mb-md-0">
        <h6 class="text-uppercase mb-1">Sistema de Reclutamiento</h6>
        <small>Plataforma para la gestión de convocatorias, postulantes y evaluaciones.</small>
      </div>
      <div class="col-lg-6 col-md-12 text-lg-end">
        <small class="d-block mb-1">© <?= date("Y"); ?> - Todos los derechos reservados</small>
        <a href="#" class="text-light me-3">Políticas de Privacidad</a>
        <a href="#" class="text-light">Términos y Condiciones</a>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
