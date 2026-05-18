<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']['IdUsuario']) || $_SESSION['user']['IdPerfil'] != 1) {
    header("Location: index.php?controller=login&action=index&error=permiso");
    exit;
}

// Nombre completo del administrador
$nombreCompleto = htmlspecialchars(
    $_SESSION['user']['Nombre'] . ' ' .
    $_SESSION['user']['ApellidoPaterno'] . ' ' .
    $_SESSION['user']['ApellidoMaterno']
);

// Datos simulados
$totalConvocatoriasActivas = $totalConvocatoriasActivas ?? 5;
$totalUsuariosActivos = $totalUsuariosActivos ?? 12;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel de Administración</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    html, body { height: 100%; margin: 0; }
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      overflow: hidden;
      background-color: #f8f9fa;
    }
    main { flex: 1; overflow-y: auto; padding-bottom: 80px; }
    footer { position: fixed; bottom: 0; left: 0; width: 100%; z-index: 1030; }

    .navbar-custom, .footer-custom {
      background-color: #34495e !important;
    }

    .card-dashboard {
      max-width: 320px;
      width: 100%;
      margin: 0 auto;
      border: 1px solid #dee2e6;
      border-radius: 1rem;
      background: #ffffff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      cursor: pointer;
      perspective: 1000px;
      position: relative;
      height: 220px;
    }
    .card-dashboard:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 18px rgba(0,0,0,0.15);
    }

    .flip-card-inner {
      position: relative;
      width: 100%;
      height: 100%;
      text-align: center;
      transition: transform 0.6s;
      transform-style: preserve-3d;
      border-radius: 1rem;
    }

    .card-dashboard.flipped .flip-card-inner {
      transform: rotateY(180deg);
    }

    .flip-card-front, .flip-card-back {
      position: absolute;
      width: 100%;
      height: 100%;
      backface-visibility: hidden;
      border-radius: 1rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 20px;
      box-sizing: border-box;
    }

    .flip-card-front {
      background: #ffffff;
      color: #000;
    }

    .flip-card-back {
      background: #0d6efd;
      color: #fff;
      transform: rotateY(180deg);
      font-size: 1.1rem;
      font-weight: 600;
      padding: 30px 20px;
    }

    .icon-circle {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px auto;
      font-size: 2rem;
      color: #fff;
    }
    .icon-primary { background: #0d6efd; }
    .icon-success { background: #198754; }
    .icon-warning { background: #ffc107; }
    .icon-info { background: #0dcaf0; }

    .card-option {
      transition: all 0.3s ease;
      border-radius: 1rem;
      text-align: center;
      padding: 30px 20px;
      background: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      text-decoration: none;
      color: inherit;
    }

    .card-option:hover {
      transform: translateY(-5px);
      background-color: #198754;
      color: #fff !important;
    }

    .card-option i {
      font-size: 2rem;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow">
  <div class="container">
    <a class="navbar-brand fw-bold">
      <i class="bi bi-briefcase-fill me-2"></i>Sistema de Reclutamiento
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link active" href="index.php?controller=admin&action=index">
            <i class="bi bi-house-door-fill me-1"></i>Inicio
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?controller=convocatoria&action=index">
            <i class="bi bi-file-earmark-text-fill me-1"></i>Convocatorias
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?controller=usuario&action=index">
            <i class="bi bi-people-fill me-1"></i>Usuarios
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?controller=reporteria&action=index">
            <i class="bi bi-check2-square me-1"></i>Reportería
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger" href="index.php?controller=login&action=logout">
            <i class="bi bi-box-arrow-right me-1"></i>Cerrar sesión
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- CONTENIDO -->
<main class="container mt-4 mb-5">
  <h2 class="mb-4">Bienvenido, <?= $nombreCompleto; ?> 👋</h2>

  <div class="row g-4 mb-4 justify-content-center">
    <!-- Convocatorias activas -->
    <div class="col-md-4 d-flex justify-content-center">
      <div class="card card-dashboard" onclick="flipCard(this)">
        <div class="flip-card-inner">
          <div class="flip-card-front">
            <div class="icon-circle icon-primary"><i class="bi bi-file-earmark-text-fill"></i></div>
            <h5 class="card-title">Convocatorias activas</h5>
            <p class="fs-3 fw-bold text-primary"><?= $totalConvocatoriasActivas; ?></p>
          </div>
          <div class="flip-card-back">
            <p>Gestiona y revisa todas las convocatorias activas disponibles.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Usuarios activos -->
    <div class="col-md-4 d-flex justify-content-center">
      <div class="card card-dashboard" onclick="flipCard(this)">
        <div class="flip-card-inner">
          <div class="flip-card-front">
            <div class="icon-circle icon-success"><i class="bi bi-people-fill"></i></div>
            <h5 class="card-title">Usuarios</h5>
            <p class="fs-3 fw-bold text-success"><?= $totalUsuariosActivos; ?></p>
          </div>
          <div class="flip-card-back">
            <p>Administra los usuarios registrados en el sistema.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Reloj -->
    <div class="col-md-4 d-flex justify-content-center">
      <div class="card card-dashboard" onclick="flipCard(this)">
        <div class="flip-card-inner">
          <div class="flip-card-front">
            <div class="icon-circle icon-info"><i class="bi bi-clock-history"></i></div>
            <h5 class="card-title">Hora actual</h5>
            <p id="reloj-evaluaciones" class="fs-3 fw-bold text-info">--:--:--</p>
          </div>
          <div class="flip-card-back">
            <p>La hora actual del sistema actualizada en tiempo real.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Opciones rápidas -->
  <div class="row g-4 justify-content-center">
    <div class="col-md-3">
      <a href="index.php?controller=area&action=index" class="card-option d-block">
        <i class="bi bi-folder-plus"></i>
        <h6 class="mt-2">Gestión de Áreas</h6>
        <small class="text-muted d-block">Crear, Listar, Editar y Eliminar</small>
      </a>
    </div>
    <div class="col-md-3">
      <a href="index.php?controller=area&action=estado" class="card-option d-block">
        <i class="bi bi-toggle-on"></i>
        <h6 class="mt-2">Estados de Áreas</h6>
        <small class="text-muted d-block">Revisa Historial de las Áreas Eliminadas</small>
      </a>
    </div>
    <div class="col-md-3">
      <a href="index.php?controller=convocatoria&action=buscarPorArea" class="card-option d-block">
        <i class="bi bi-search"></i>
        <h6 class="mt-2">Buscar Área - Convocatoria</h6>
        <small class="text-muted d-block">Búsqueda Personalizada</small>
      </a>
    </div>
  </div>
</main>

<!-- FOOTER -->
<footer class="footer-custom text-light text-center text-lg-start">
  <div class="container p-3">
    <div class="row">
      <div class="col-lg-6 col-md-12 mb-2 mb-md-0">
        <h6 class="text-uppercase mb-1">Sistema de Reclutamiento</h6>
        <small>Plataforma para la gestión de convocatorias, postulantes y más.</small>
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
<script>
  function flipCard(card) {
    card.classList.toggle('flipped');
  }

  function actualizarReloj() {
    const reloj = document.getElementById('reloj-evaluaciones');
    const ahora = new Date();
    const hora = ahora.toLocaleTimeString();
    reloj.textContent = hora;
  }

  setInterval(actualizarReloj, 1000);
  window.onload = actualizarReloj;
</script>
</body>
</html>
