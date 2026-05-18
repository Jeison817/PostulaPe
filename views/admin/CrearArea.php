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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nueva Área</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
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
            max-width: 600px;
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
            background-color: #28a745;
            border: none;
        }

        .btn-primary:hover {
            background-color: #218838;
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

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center">
      <i class="bi bi-briefcase-fill me-2"></i>
      Sistema de Reclutamiento
      <span class="ms-2 fw-normal fs-6">/ <?= htmlspecialchars($nombreCompleto) ?></span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>

<!-- FORMULARIO -->
<main class="container">
  <div class="card form-card p-4">
      <h2 class="mb-4 text-center">Nueva Área</h2>

      <?php if (!empty($error)) : ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form action="index.php?controller=area&action=guardar" method="post">
          <div class="mb-3">
              <label for="Nombre" class="form-label">Nombre del Área</label>
              <input type="text" name="Nombre" id="Nombre" class="form-control form-control-sm" required placeholder="Ej: Tecnología, Finanzas, etc.">
          </div>

          <div class="mb-3">
              <label for="Descripcion" class="form-label">Descripción</label>
              <textarea name="Descripcion" id="Descripcion" class="form-control form-control-sm" rows="3" placeholder="Descripción opcional..."></textarea>
          </div>

          <div class="d-flex justify-content-between mt-4">
              <a href="index.php?controller=area&action=index" class="btn btn-secondary btn-sm">
                  <i class="bi bi-arrow-left"></i> Cancelar
              </a>
              <button type="submit" class="btn btn-primary btn-sm">
                  <i class="bi bi-save me-1"></i> Guardar
              </button>
          </div>
      </form>
  </div>
</main>

<!-- FOOTER -->
<footer class="footer-custom text-light text-center text-lg-start">
  <div class="container p-3">
    <div class="row">
      <div class="col-lg-6 col-md-12 mb-2 mb-md-0">
        <h6 class="text-uppercase mb-1">Sistema de Reclutamiento</h6>
        <small>
          Plataforma para la gestión de convocatorias, postulantes y evaluaciones.
        </small>
      </div>
      <div class="col-lg-6 col-md-12 text-lg-end">
        <small class="d-block mb-1">© <?= date("Y"); ?> - Todos los derechos reservados</small>
        <a href="#" class="text-light me-3">Políticas de Privacidad</a>
        <a href="#" class="text-light">Términos y Condiciones</a>
      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
