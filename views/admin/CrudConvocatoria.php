<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nombreCompleto  = isset($_SESSION['user']) 
    ? ($_SESSION['user']['Nombre'] . ' ' . $_SESSION['user']['ApellidoPaterno'] . ' ' . $_SESSION['user']['ApellidoMaterno'])
    : 'Invitado';

$registrosPorPagina = 4;
$paginaActual = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$paginaActual = max($paginaActual, 1);

$totalRegistros = count($convocatorias ?? []);
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
$inicio = ($paginaActual - 1) * $registrosPorPagina;
$convocatoriasPaginadas = array_slice($convocatorias ?? [], $inicio, $registrosPorPagina);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Gestión de Convocatorias</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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
      overflow: hidden;
      background-color: #ecf0f1;
    }
    main {
      flex: 1;
      overflow-y: auto;
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
    .table-custom {
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .table-custom thead th {
      background-color: #3498db;
      color: #fff;
    }
    .table-custom tbody td {
      background-color: #ffffff;
    }
    .table-custom tbody tr:nth-child(even) td {
      background-color: #f8f9fa;
    }
    .table-custom tbody tr:hover td {
      background-color: #eaf4fc;
    }
    .btn-success {
      background-color: #198754;
      border: none;
    }
    .btn-success:hover {
      background-color: #157347;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php?controller=admin&action=index">
      <i class="bi bi-building me-2"></i>
      Sistema de Reclutamiento
      <span class="ms-2 fw-normal fs-6">/ <?= htmlspecialchars($nombreCompleto) ?></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="index.php?controller=admin&action=index"><i class="bi bi-house-door-fill me-1"></i>Inicio</a></li>
        <li class="nav-item"><a class="nav-link active" href="index.php?controller=convocatoria&action=index"><i class="bi bi-file-earmark-text-fill me-1"></i>Convocatorias</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?controller=usuario&action=index"><i class="bi bi-people-fill me-1"></i>Usuarios</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?controller=reporteria&action=index"><i class="bi bi-check2-square me-1"></i>Reporteria</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="index.php?controller=login&action=logout"><i class="bi bi-box-arrow-right me-1"></i>Cerrar sesión</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold">Gestión de Convocatorias</h2>
      <a href="index.php?controller=convocatoria&action=crear" class="btn btn-success">+ Nueva Convocatoria</a>
  </div>

  <?php if (!empty($convocatoriasPaginadas)) : ?>
      <div class="table-responsive">
          <table class="table table-bordered align-middle text-center table-custom">
              <thead>
                  <tr>
                      <th>ID</th>
                      <th>Título</th>
                      <th>Descripción</th>
                      <th>Fecha Inicio</th>
                      <th>Fecha Fin</th>
                      <th>Área</th>
                      <th>Acciones</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($convocatoriasPaginadas as $convocatoria) : ?>
                      <tr>
                          <td><?= htmlspecialchars($convocatoria['IdConvocatoria']) ?></td>
                          <td><?= htmlspecialchars($convocatoria['Titulo']) ?></td>
                          <td><?= htmlspecialchars($convocatoria['Descripcion']) ?></td>
                          <td><?= htmlspecialchars($convocatoria['FechaInicio']) ?></td>
                          <td><?= htmlspecialchars($convocatoria['FechaFin']) ?></td>
                          <td><?= htmlspecialchars($convocatoria['NombreArea']) ?></td>
                          <td>
  <div class="d-flex justify-content-center gap-2">
    <a href="index.php?controller=convocatoria&action=editar&id=<?= $convocatoria['IdConvocatoria'] ?>" class="btn btn-sm btn-warning d-flex align-items-center">
      <i class="bi bi-pencil-fill me-1"></i> Editar
    </a>
    <a href="index.php?controller=convocatoria&action=eliminar&id=<?= $convocatoria['IdConvocatoria'] ?>" class="btn btn-sm btn-danger d-flex align-items-center" onclick="return confirm('¿Seguro que deseas eliminar esta convocatoria?');">
      <i class="bi bi-trash-fill me-1"></i> Eliminar
    </a>
    <a href="index.php?controller=convocatoria&action=resultados&id=<?= $convocatoria['IdConvocatoria'] ?>" class="btn btn-sm btn-info text-white d-flex align-items-center">
  <i class="bi bi-eye-fill me-1"></i> Ver Resultado
</a>
  </div>
</td>
                      </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      </div>

      <nav>
        <ul class="pagination justify-content-center">
          <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
            <li class="page-item <?= ($i === $paginaActual) ? 'active' : '' ?>">
              <a class="page-link" href="index.php?controller=convocatoria&action=index&page=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>

  <?php else : ?>
      <div class="alert alert-info shadow-sm">No hay convocatorias registradas.</div>
  <?php endif; ?>
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
