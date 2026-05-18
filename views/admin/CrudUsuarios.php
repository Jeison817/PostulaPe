<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nombreCompleto = isset($_SESSION['user']) 
    ? ($_SESSION['user']['Nombre'] . ' ' . $_SESSION['user']['ApellidoPaterno'] . ' ' . $_SESSION['user']['ApellidoMaterno']) 
    : 'Invitado';

$registrosPorPagina = 4;
$paginaActual = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$paginaActual = max($paginaActual, 1);

$totalRegistros = count($usuarios ?? []);
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
$inicio = ($paginaActual - 1) * $registrosPorPagina;
$usuariosPaginados = array_slice($usuarios ?? [], $inicio, $registrosPorPagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Gestión de Usuarios</title>
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
  color: #fff;
  border: none;
  box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
  transition: background-color 0.3s, box-shadow 0.3s;
}

.btn-custom-create:hover {
  background-color: #218838;
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.45);
}

.btn-eliminados {
  background: linear-gradient(135deg, #cfd9df 0%, #e2ebf0 100%);
  color: #333;
  border: 1px solid #ccc;
  transition: background 0.3s, color 0.3s;
  font-weight: 500;
}

.btn-eliminados:hover {
  background: linear-gradient(135deg, #dfe9f3 0%, #ffffff 100%);
  color: #000;
  border-color: #bbb;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

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
      <i class="bi bi-person-fill-gear me-2"></i>
      Gestión de Usuarios
      <span class="ms-2 fw-normal fs-6">/ <?= htmlspecialchars($nombreCompleto) ?></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavUsuario">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavUsuario">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php?controller=admin&action=index"><i class="bi bi-house-door-fill me-1"></i>Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?controller=convocatoria&action=index"><i class="bi bi-file-earmark-text-fill me-1"></i>Convocatorias</a></li>
        <li class="nav-item"><a class="nav-link active" href="index.php?controller=usuario&action=index"><i class="bi bi-people-fill me-1"></i>Usuarios</a></li>
        <li class="nav-item"><a class="nav-link"  href="index.php?controller=reporteria&action=index"><i class="bi bi-check2-square me-1"></i>Reporteria</a>
        </li>
        <li class="nav-item"><a class="nav-link text-danger" href="index.php?controller=login&action=logout"><i class="bi bi-box-arrow-right me-1"></i>Cerrar sesión</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container">
  <div class="mb-3">
  <h2 class="fw-bold">Gestión de Usuarios</h2>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <div>
    <a href="index.php?controller=usuario&action=obtenerHistorialEliminados" class="btn btn-eliminados btn-sm">
  <i class="bi bi-archive-fill me-1"></i> Historial de Eliminados
</a>
  </div>
  <div>
    <a href="index.php?controller=usuario&action=crear" class="btn btn-custom-create btn-sm">
      <i class="bi bi-plus-lg"></i> Nuevo Usuario
    </a>
  </div>
</div>


  <!-- Cards de usuarios activos e inactivos -->
  <div class="d-flex justify-content-center mb-3 gap-2">
    <div class="card text-white shadow-sm" style="max-width: 320px; width: 100%; background: linear-gradient(135deg, #6dd5fa 0%, #2980b9 100%); height: 110px; position: relative; overflow: hidden; border-radius: 15px;">
      <i class="bi bi-person-check-fill" style="position: absolute; top: 12px; right: 12px; font-size: 3.5rem; opacity: 0.15;"></i>
      <div class="card-body d-flex flex-column justify-content-center ps-4" style="position: relative; z-index: 1;">
        <h4 class="card-title mb-0">Usuarios Activos</h4>
        <p class="card-text fs-2 fw-bold mb-0"><?= htmlspecialchars($cantidadActivos ?? 0) ?></p>
      </div>
    </div>

    <div class="card text-white shadow-sm" style="max-width: 320px; width: 100%; background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); height: 110px; position: relative; overflow: hidden; border-radius: 15px;">
      <i class="bi bi-person-dash-fill" style="position: absolute; top: 12px; right: 12px; font-size: 3.5rem; opacity: 0.15;"></i>
      <div class="card-body d-flex flex-column justify-content-center ps-4" style="position: relative; z-index: 1;">
        <h4 class="card-title mb-0">Usuarios Inactivos</h4>
        <p class="card-text fs-2 fw-bold mb-0"><?= htmlspecialchars($cantidadInactivos ?? 0) ?></p>
      </div>
    </div>
  </div>

  <?php if (!empty($usuariosPaginados)) : ?>
    <div class="table-responsive mb-4">
      <table class="table table-hover align-middle text-center table-custom">
        <thead>
          <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Nombre Completo</th>
            <th>Perfil</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($usuariosPaginados as $usuario) : ?>
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
              <td>
  <a href="index.php?controller=usuario&action=editar&id=<?= $usuario['IdUsuario'] ?>" class="btn btn-sm btn-warning btn-action me-1" title="Editar Usuario">
      <i class="bi bi-pencil-fill"></i> Editar
  </a>
  <a href="index.php?controller=usuario&action=eliminar&id=<?= $usuario['IdUsuario'] ?>" class="btn btn-sm btn-danger btn-action" title="Eliminar Usuario" onclick="return confirm('¿Está seguro que desea eliminar este usuario?');">
      <i class="bi bi-trash-fill"></i> Eliminar
  </a>
</td>

            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    <nav aria-label="Paginación Usuarios" class="mb-4">
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
          <li class="page-item <?= ($i === $paginaActual) ? 'active' : '' ?>">
            <a class="page-link" href="index.php?controller=usuario&action=index&page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php else : ?>
    <div class="alert alert-info shadow-sm">No hay usuarios registrados.</div>
  <?php endif; ?>
</main>

<footer class="footer-custom text-light text-center text-lg-start">
  <div class="container p-3">
    <div class="row">
      <div class="col-lg-6 col-md-12 mb-2 mb-md-0">
        <h6 class="text-uppercase mb-1">Sistema de Reclutamiento</h6>
        <small>Plataforma para la gestión de usuarios, perfiles y accesos.</small>
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
