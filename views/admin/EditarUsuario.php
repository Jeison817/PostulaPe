<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['IdPerfil'] != 1) {
    header("Location: login.php?error=permiso");
    exit;
}

$nombreCompleto = isset($_SESSION['user'])
    ? trim(
        ($_SESSION['user']['Nombre'] ?? '') . ' ' .
        ($_SESSION['user']['ApellidoPaterno'] ?? '') . ' ' .
        ($_SESSION['user']['ApellidoMaterno'] ?? '')
    )
    : 'Usuario no identificado';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Editar Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            background-color: #ecf0f1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            padding-bottom: 80px;
        }

        .navbar-custom, .footer-custom {
            background-color: #34495e !important;
        }

        .form-card {
            max-width: 700px;
            margin: 40px auto;
            padding: 30px;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 500;
            font-size: 0.95rem;
        }

        .form-control::placeholder {
            color: #aaa;
            font-style: italic;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center">
            <i class="bi bi-person-fill me-2"></i>
            Sistema de Reclutamiento
            <span class="ms-2 fw-normal fs-6">/ <?= htmlspecialchars($nombreCompleto) ?></span>
        </a>
    </div>
</nav>

<!-- CONTENIDO -->
<main class="container">
    <div class="card form-card">
        <h2 class="mb-4 text-center">Editar Usuario</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
        <form action="index.php?controller=usuario&action=actualizar&id=<?= htmlspecialchars($usuario['IdUsuario']) ?>" method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="Nombre" class="form-label">Nombre</label>
                    <input type="text" name="Nombre" id="Nombre" class="form-control form-control-sm" required
                        value="<?= htmlspecialchars($usuario['Nombre']) ?>" placeholder="Ej: Juan">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="ApellidoPaterno" class="form-label">Apellido Paterno</label>
                    <input type="text" name="ApellidoPaterno" id="ApellidoPaterno" class="form-control form-control-sm" required
                        value="<?= htmlspecialchars($usuario['ApellidoPaterno']) ?>" placeholder="Ej: Pérez">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="ApellidoMaterno" class="form-label">Apellido Materno</label>
                    <input type="text" name="ApellidoMaterno" id="ApellidoMaterno" class="form-control form-control-sm" required
                        value="<?= htmlspecialchars($usuario['ApellidoMaterno']) ?>" placeholder="Ej: Rodríguez">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="UsuarioBase" class="form-label">Nombre de Usuario</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="UsuarioBase" id="UsuarioBase" class="form-control" required
                            value="<?= htmlspecialchars(str_replace('@system.com', '', $usuario['Usuario'])) ?>"
                            placeholder="Ej: juanperez123">
                        <span class="input-group-text">@system.com</span>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="Contrasena" class="form-label">Nueva Contraseña</label>
                    <input type="password" name="Contrasena" id="Contrasena" class="form-control form-control-sm"
                        placeholder="Déjalo en blanco para no cambiar">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="IdPerfil" class="form-label">Perfil</label>
                    <select name="IdPerfil" id="IdPerfil" class="form-select form-select-sm" required>
                        <option value="1" <?= $usuario['IdPerfil'] == 1 ? 'selected' : '' ?>>Administrador</option>
                        <option value="3" <?= $usuario['IdPerfil'] == 3 ? 'selected' : '' ?>>Evaluador</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="index.php?controller=usuario&action=index" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-arrow-repeat me-1"></i> Actualizar
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
                <small>Gestión de convocatorias, postulantes y evaluaciones.</small>
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
