<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nombreCompleto  = isset($_SESSION['user']) 
    ? ($_SESSION['user']['Nombre'] . ' ' . $_SESSION['user']['ApellidoPaterno'] . ' ' . $_SESSION['user']['ApellidoMaterno'])
    : 'Invitado';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Buscar Convocatorias por Área</title>
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
        <li class="nav-item">
          <a class="nav-link" href="index.php?controller=admin&action=index"><i class="bi bi-house-door-fill me-1"></i>Inicio</a>
        </li>
        <li class="nav-item"><a class="nav-link active" href="#"><i class="bi bi-search me-1"></i>Buscar Convocatorias</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container mt-4">
  <h2 class="fw-bold mb-4">Buscar Convocatorias por Área</h2>

  <form method="POST" action="index.php?controller=convocatoria&action=buscarPorArea" class="row mb-4">
    <div class="col-md-6">
      <label for="IdArea" class="form-label">Área:</label>
      <select name="IdArea" id="IdArea" class="form-select" required>
        <option value="">-- Selecciona un área --</option>
        <?php foreach ($areas as $area): ?>
          <option value="<?= $area['IdArea'] ?>" <?= (isset($_POST['IdArea']) && $_POST['IdArea'] == $area['IdArea']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($area['Nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button type="submit" class="btn btn-primary">Buscar</button>
    </div>  
  </form>

  <?php if (!empty($convocatorias)): ?>
    <h4 class="mb-3">Resultados encontrados:</h4>
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
          </tr>
        </thead>
        <tbody>
          <?php foreach ($convocatorias as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c['ID']) ?></td>
              <td><?= htmlspecialchars($c['Titulo']) ?></td>
              <td><?= htmlspecialchars($c['Descripcion']) ?></td>
              <td><?= htmlspecialchars($c['FechaInicio']) ?></td>
              <td><?= htmlspecialchars($c['FechaFin']) ?></td>
              <td><?= htmlspecialchars($c['Area']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <div class="alert alert-warning">No se encontraron convocatorias para el área seleccionada.</div>
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
