<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nombreCompleto = isset($_SESSION['user']) 
    ? ($_SESSION['user']['Nombre'] . ' ' . $_SESSION['user']['ApellidoPaterno'] . ' ' . $_SESSION['user']['ApellidoMaterno'])
    : 'Invitado';

$registrosPorPagina = 5;
$paginaActual = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$paginaActual = max($paginaActual, 1);

$totalRegistros = count($areasEliminadas ?? []);
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
$inicio = ($paginaActual - 1) * $registrosPorPagina;
$areasPaginadas = array_slice($areasEliminadas ?? [], $inicio, $registrosPorPagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Historial de Áreas Eliminadas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f7f9fc;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    main {
      flex-grow: 1;
      padding-top: 20px;
      padding-bottom: 60px;
    }
    footer {
      background-color: #34495e;
      color: #fff;
      padding: 10px 0;
      position: fixed;
      bottom: 0;
      width: 100%;
      text-align: center;
      font-size: 0.9rem;
    }

    .table-custom {
      border: 1px solid #dee2e6;
      border-radius: 8px;
      overflow: hidden;
      background-color: #ffffff;
      box-shadow: 0 3px 8px rgba(0,0,0,0.05);
    }

    .table-custom thead th {
      background-color: #dc3545;
      color: white;
      vertical-align: middle;
      text-transform: uppercase;
      font-size: 0.85rem;
    }

    .table-custom tbody tr:nth-child(even) {
      background-color: #f8f9fa;
    }

    .table-custom tbody tr:hover {
      background-color: #eaf4fc;
    }

    .table-custom td, .table-custom th {
      padding: 12px 10px;
      vertical-align: middle;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #34495e;">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php?controller=admin&action=index">
      <i class="bi bi-building me-2"></i>
      Sistema de Reclutamiento
      <span class="ms-2 fw-normal fs-6">/ <?= htmlspecialchars($nombreCompleto) ?></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavArea">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavArea">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php?controller=admin&action=index">
            <i class="bi bi-house-door-fill me-1"></i>Inicio
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="index.php?controller=area&action=index">
  <i class="bi bi-grid-fill me-1"></i>Áreas
</a>

        </li>
      </ul>
    </div>
  </div>
</nav>

<main class="container">
  <div class="mb-4">
    <h2 class="fw-bold text-danger"><i class="bi bi-archive-fill me-2"></i>Historial de Áreas Eliminadas</h2>
  </div>

  <?php if (!empty($areasPaginadas)) : ?>
    <div class="table-responsive mb-4">
      <table class="table table-hover align-middle text-center table-custom">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Fecha Modificación</th>
            <th>Modificado por</th>
            <th>Creado por</th>
            <th>Fecha Creación</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($areasPaginadas as $area) : ?>
            <tr>
              <td><?= htmlspecialchars($area['IdArea']) ?></td>
              <td><?= htmlspecialchars($area['Nombre']) ?></td>
              <td><?= htmlspecialchars($area['Descripcion']) ?></td>
              <td><?= $area['FechaModificacion'] ?? '<span class="text-muted">-</span>' ?></td>
              <td><?= htmlspecialchars($area['ModificadorNombre'] . ' ' . $area['ModificadorApellidoPaterno'] . ' ' . $area['ModificadorApellidoMaterno']) ?></td>
              <td><?= htmlspecialchars($area['CreadorNombre'] . ' ' . $area['CreadorApellidoPaterno'] . ' ' . $area['CreadorApellidoMaterno']) ?></td>
              <td><?= htmlspecialchars($area['FechaCreacion']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    <nav aria-label="Paginación Historial">
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
          <li class="page-item <?= ($i === $paginaActual) ? 'active' : '' ?>">
            <a class="page-link" href="index.php?controller=area&action=estado&page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>

  <?php else : ?>
    <div class="alert alert-info shadow-sm">No hay áreas eliminadas registradas.</div>
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
