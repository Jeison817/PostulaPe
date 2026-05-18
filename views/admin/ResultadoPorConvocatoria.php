<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nombreCompleto = isset($_SESSION['user']) 
    ? ($_SESSION['user']['Nombre'] . ' ' . $_SESSION['user']['ApellidoPaterno'] . ' ' . $_SESSION['user']['ApellidoMaterno']) 
    : 'Invitado';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Resultados de la Convocatoria</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body, html {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
    }
    main {
      flex: 1 0 auto;
    }
    footer.footer-custom {
      flex-shrink: 0;
      background-color: #34495e;
      padding: 1rem 0;
    }

    /* Estilos tabla igual que ejemplo usuarios */
    .table-custom {
      border: 1px solid #dee2e6;
      border-radius: 8px;
      overflow: hidden;
      background-color: #ffffff;
      box-shadow: 0 3px 8px rgba(0,0,0,0.05);
    }

    .table-custom thead th {
      background-color: #343a40;
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
      <i class="bi bi-bar-chart-steps me-2"></i>
      Resultados de Convocatoria
      <span class="ms-2 fw-normal fs-6">/ <?= htmlspecialchars($nombreCompleto) ?></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResultados">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarResultados">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="index.php?controller=admin&action=index">
            <i class="bi bi-house-door-fill me-1"></i>Inicio
          </a>
        </li>
        <li class="nav-item ms-2">
          <a href="index.php?controller=convocatoria&action=exportarPdf&id=<?= $convocatoria['IdConvocatoria'] ?>" 
             class="btn btn-danger btn-sm" 
             target="_blank" 
             title="Exportar resultados a PDF">
            <i class="bi bi-file-earmark-pdf me-1"></i> Descargar PDF
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark">
      <i class="bi bi-person-lines-fill me-2 text-primary"></i> Postulantes Seleccionados
    </h2>
    <a href="index.php?controller=convocatoria&action=index" class="btn btn-secondary btn-sm">
      <i class="bi bi-arrow-left"></i> Volver al Listado
    </a>
  </div>

  <div class="mb-3">
    <h4 class="text-primary"><?= htmlspecialchars($convocatoria['Titulo']) ?></h4>
    <p class="text-muted">
      A continuación se muestran los postulantes seleccionados para esta convocatoria. Los resultados reflejan las evaluaciones realizadas según los criterios técnicos establecidos por el comité evaluador.
    </p>
  </div>

  <?php if (!empty($resultados)): ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle text-center table-custom">
        <thead>
          <tr>
            <th>Postulante</th>
            <th>Tipo Doc.</th>
            <th>Documento</th>
            <th>Celular</th>
            <th>Área</th>
            <th>Nota Final</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($resultados as $fila): ?>
            <tr>
              <td><?= htmlspecialchars($fila['NombrePostulante']) ?></td>
              <td><?= htmlspecialchars($fila['TipoDocumento']) ?></td>
              <td><?= htmlspecialchars($fila['NumeroDocumento']) ?></td>
              <td><?= htmlspecialchars($fila['Celular']) ?></td>
              <td><?= htmlspecialchars($fila['Area']) ?></td>
              <td><strong><?= htmlspecialchars($fila['NotaFinal']) ?></strong></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info">No hay postulantes seleccionados para esta convocatoria.</div>
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
