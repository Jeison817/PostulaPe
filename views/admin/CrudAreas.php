<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nombreCompleto  = isset($_SESSION['user']) 
    ? ($_SESSION['user']['Nombre'] . ' ' . $_SESSION['user']['ApellidoPaterno'] . ' ' . $_SESSION['user']['ApellidoMaterno'])
    : 'Invitado';

$registrosPorPagina = 5;
$paginaActual = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$paginaActual = max($paginaActual, 1);

$totalRegistros = count($areas ?? []);
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
$inicio = ($paginaActual - 1) * $registrosPorPagina;
$areasPaginadas = array_slice($areas ?? [], $inicio, $registrosPorPagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Gestión de Áreas</title>
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

    .btn-custom-create {
      background-color: #28a745;
      color: white;
      border: none;
    }
    .btn-custom-create:hover {
      background-color: #218838;
    }

    .table-custom {
      border: 1px solid #dee2e6;
      border-radius: 8px;
      overflow: hidden;
      background-color: #ffffff;
      box-shadow: 0 3px 8px rgba(0,0,0,0.05);
    }

    .table-custom thead th {
      background-color: #007bff;
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

    .btn-action {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 4px;
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
        <li class="nav-item"><a class="nav-link" href="index.php?controller=admin&action=index"><i class="bi bi-house-door-fill me-1"></i>Inicio</a></li>
        <li class="nav-item"><a class="nav-link active" href="index.php?controller=area&action=index"><i class="bi bi-folder-fill me-1"></i>Áreas</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Gestión de Áreas</h2>
    <a href="index.php?controller=area&action=crear" class="btn btn-custom-create">
      <i class="bi bi-plus-lg"></i> Nueva Área
    </a>
  </div>

  <!-- Cards para áreas activas e inactivas -->
  <div class="d-flex justify-content-center mb-3 gap-2">
  <div class="card text-white shadow-sm" style="max-width: 320px; width: 100%; background: linear-gradient(135deg, #6dd5fa 0%, #2980b9 100%); height: 110px; position: relative; overflow: hidden; border-radius: 15px;">
    <i class="bi bi-check-circle-fill" style="position: absolute; top: 12px; right: 12px; font-size: 3.5rem; opacity: 0.15;"></i>
    <div class="card-body d-flex flex-column justify-content-center ps-4" style="position: relative; z-index: 1;">
      <h4 class="card-title mb-0">Áreas Activas</h4>
      <p class="card-text fs-2 fw-bold mb-0"><?= htmlspecialchars($cantidadActivas ?? 0) ?></p>
    </div>
  </div>

  <div class="card text-white shadow-sm" style="max-width: 320px; width: 100%; background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); height: 110px; position: relative; overflow: hidden; border-radius: 15px;">
    <i class="bi bi-x-circle-fill" style="position: absolute; top: 12px; right: 12px; font-size: 3.5rem; opacity: 0.15;"></i>
    <div class="card-body d-flex flex-column justify-content-center ps-4" style="position: relative; z-index: 1;">
      <h4 class="card-title mb-0">Áreas Inactivas</h4>
      <p class="card-text fs-2 fw-bold mb-0"><?= htmlspecialchars($cantidadInactivas ?? 0) ?></p>
    </div>
  </div>
</div>



  <?php if (!empty($areasPaginadas)) : ?>
    <div class="table-responsive mb-4">
      <table class="table table-hover align-middle text-center table-custom">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($areasPaginadas as $area) : ?>
            <tr>
              <td><?= htmlspecialchars($area['IdArea']) ?></td>
              <td><?= htmlspecialchars($area['Nombre']) ?></td>
              <td><?= htmlspecialchars($area['Descripcion']) ?></td>
              <td>
                <?php if ($area['IdEliminado'] == 0): ?>
                  <a href="index.php?controller=area&action=editar&id=<?= $area['IdArea'] ?>" class="btn btn-sm btn-warning btn-action me-1" title="Editar Área">
                    <i class="bi bi-pencil-fill"></i> Editar
                  </a>
                  <a href="index.php?controller=area&action=eliminar&id=<?= $area['IdArea'] ?>" class="btn btn-sm btn-danger btn-action" title="Eliminar Área" onclick="return confirm('¿Está seguro que desea eliminar esta área?');">
                    <i class="bi bi-trash-fill"></i> Eliminar
                  </a>
                <?php else: ?>
                  <a href="index.php?controller=area&action=reactivar&id=<?= $area['IdArea'] ?>" class="btn btn-sm btn-success btn-action" title="Reactivar Área">
                    <i class="bi bi-arrow-counterclockwise"></i> Reactivar
                  </a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    <nav aria-label="Paginación Áreas">
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
          <li class="page-item <?= ($i === $paginaActual) ? 'active' : '' ?>">
            <a class="page-link" href="index.php?controller=area&action=index&page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>

  <?php else : ?>
    <div class="alert alert-info shadow-sm">No hay áreas registradas.</div>
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
