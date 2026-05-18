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
  <title>Reportería General</title>
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
    .btn-danger {
      background-color: #e74c3c;
      border: none;
    }
    .btn-danger:hover {
      background-color: #c0392b;
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
        <li class="nav-item"><a class="nav-link" href="index.php?controller=convocatoria&action=index"><i class="bi bi-file-earmark-text-fill me-1"></i>Convocatorias</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?controller=usuario&action=index"><i class="bi bi-people-fill me-1"></i>Usuarios</a></li>
        <li class="nav-item"><a class="nav-link active"><i class="bi bi-check2-square me-1"></i>Reportería</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="index.php?controller=login&action=logout"><i class="bi bi-box-arrow-right me-1"></i>Cerrar sesión</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold">Resultados Generales de Postulantes Seleccionados</h2>
      <a href="index.php?controller=reporteria&action=exportarPdfResultadosGenerales" class="btn btn-danger">
    🧾 Exportar PDF
</a>
  </div>

  <?php if (!empty($resultados)) : ?>
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center table-custom">
        <thead>
          <tr>
            <th>Convocatoria</th>
            <th>Área</th>
            <th>Postulante</th>
            <th>Tipo Doc.</th>
            <th>N° Documento</th>
            <th>Teléfono</th>
            <th>Celular</th>
            <th>Dirección</th>
            <th>Departamento</th>
            <th>Provincia</th>
            <th>Distrito</th>
            <th>Evaluador</th>
            <th>Nota Final</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($resultados as $fila): ?>
            <tr>
              <td><?= htmlspecialchars($fila['Convocatoria']) ?></td>
              <td><?= htmlspecialchars($fila['Area']) ?></td>
              <td><?= htmlspecialchars($fila['NombrePostulante']) ?></td>
              <td><?= htmlspecialchars($fila['TipoDocumento'] ?? '') ?></td>
              <td><?= htmlspecialchars($fila['NumeroDocumento']) ?></td>
              <td><?= htmlspecialchars($fila['Telefono']) ?></td>
              <td><?= htmlspecialchars($fila['Celular']) ?></td>
              <td><?= htmlspecialchars($fila['Direccion']) ?></td>
              <td><?= htmlspecialchars($fila['Departamento']) ?></td>
              <td><?= htmlspecialchars($fila['Provincia']) ?></td>
              <td><?= htmlspecialchars($fila['Distrito']) ?></td>
              <td><?= htmlspecialchars($fila['Evaluador']) ?></td>
              <td><strong><?= number_format($fila['NotaFinal'], 2) ?></strong></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else : ?>
    <div class="alert alert-info shadow-sm">No se encontraron postulantes seleccionados.</div>
  <?php endif; ?>

  <a href="index.php?controller=convocatoria&action=index" class="btn btn-secondary mt-3">
    ← Volver a Convocatorias
  </a>
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
