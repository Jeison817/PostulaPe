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
  <title>Usuarios Eliminados</title>
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
    background-color: #34495e; /* o el color que prefieras */
    padding: 1rem 0;
  }
</style>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #34495e;">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php?controller=admin&action=index">
      <i class="bi bi-person-fill-gear me-2"></i>
      Gestión de Usuarios
      <span class="ms-2 fw-normal fs-6">/ <?= htmlspecialchars($nombreCompleto) ?></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavUsuario">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavUsuario">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="index.php?controller=admin&action=index">
            <i class="bi bi-house-door-fill me-1"></i>Inicio
          </a>
        </li>
        <!-- Aquí va el botón exportar PDF -->
        <li class="nav-item ms-2">
          <a href="index.php?controller=usuario&action=exportarPdf" 
             class="btn btn-danger btn-sm" 
             target="_blank" 
             title="Exportar usuarios eliminados a PDF">
            <i class="bi bi-file-earmark-pdf me-1"></i> Descargar PDF
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<main class="container mt-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Historial de Usuarios Eliminados</h2>
    <a href="index.php?controller=usuario&action=index" class="btn btn-secondary btn-sm">
      <i class="bi bi-arrow-left"></i> Volver a Activos
    </a>
  </div>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($success) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($error) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  <?php endif; ?>

  <?php if (!empty($usuarios)) : ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Nombre Completo</th>
            <th>Perfil</th>
            <th>Fecha Eliminación</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($usuarios as $usuario): ?>
            <tr>
              <td><?= htmlspecialchars($usuario['IdUsuario']) ?></td>
              <td><?= htmlspecialchars($usuario['Usuario']) ?></td>
              <td><?= htmlspecialchars($usuario['Nombre'] . ' ' . $usuario['ApellidoPaterno'] . ' ' . $usuario['ApellidoMaterno']) ?></td>
              <td>
                <?php
                  switch ($usuario['IdPerfil']) {
                    case 1: echo 'Administrador'; break;
                    case 3: echo 'Evaluador'; break;
                    default: echo 'Postulante'; break;
                  }
                ?>
              </td>
              <td><?= htmlspecialchars($usuario['FechaEliminado'] ?? '-') ?></td>
              <td>
                <a href="index.php?controller=usuario&action=reactivar&id=<?= $usuario['IdUsuario'] ?>" 
                   class="btn btn-success btn-sm" 
                   onclick="return confirm('¿Está seguro que desea reactivar este usuario?');">
                  <i class="bi bi-arrow-counterclockwise"></i> Reactivar
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info">No hay usuarios eliminados.</div>
  <?php endif; ?>

</main>


<!-- FOOTER -->
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
