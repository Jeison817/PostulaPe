<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener nombre completo correctamente
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
    <title>Editar Convocatoria</title>
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

        .form-control::placeholder,
        .form-select::placeholder {
            color: #aaa;
            font-style: italic;
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
        }

        .btn-primary:hover {
            background-color: #2e86c1;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center">
            <i class="bi bi-briefcase-fill me-2"></i>
            Sistema de Reclutamiento
            <span class="ms-2 fw-normal fs-6">/ <?= htmlspecialchars($nombreCompleto) ?></span>
        </a>
    </div>
</nav>

<!-- CONTENIDO -->
<main class="container">
    <div class="card form-card">
        <h2 class="mb-4 text-center">Editar Convocatoria</h2>

        <form action="index.php?controller=convocatoria&action=actualizar&id=<?= htmlspecialchars($convocatoria['IdConvocatoria']) ?>" method="post">
            <div class="mb-3">
                <label for="Titulo" class="form-label">Título</label>
                <input
                    type="text"
                    name="Titulo"
                    id="Titulo"
                    class="form-control form-control-sm"
                    required
                    value="<?= htmlspecialchars($convocatoria['Titulo']) ?>"
                    placeholder="Ingrese el título de la convocatoria"
                />
            </div>

            <div class="mb-3">
                <label for="Descripcion" class="form-label">Descripción</label>
                <textarea
                    name="Descripcion"
                    id="Descripcion"
                    class="form-control form-control-sm"
                    rows="3"
                    required
                    placeholder="Ingrese una breve descripción..."
                ><?= htmlspecialchars($convocatoria['Descripcion']) ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="FechaInicio" class="form-label">Fecha Inicio</label>
                    <input
                        type="date"
                        name="FechaInicio"
                        id="FechaInicio"
                        class="form-control form-control-sm"
                        required
                        value="<?= htmlspecialchars($convocatoria['FechaInicio']) ?>"
                    />
                </div>
                <div class="col-md-6 mb-3">
                    <label for="FechaFin" class="form-label">Fecha Fin</label>
                    <input
                        type="date"
                        name="FechaFin"
                        id="FechaFin"
                        class="form-control form-control-sm"
                        required
                        value="<?= htmlspecialchars($convocatoria['FechaFin']) ?>"
                    />
                </div>
            </div>

            <div class="row align-items-end">
                <div class="col-md-8 mb-3">
                    <label for="IdArea" class="form-label">Área</label>
                    <select name="IdArea" id="IdArea" class="form-select form-select-sm" required>
                        <option value="" disabled>Seleccione un área</option>
                        <?php foreach ($areas as $area): ?>
                            <option value="<?= htmlspecialchars($area['IdArea']) ?>"
                                <?= ($area['IdArea'] == $convocatoria['IdArea']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($area['Nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3 text-end">
                    <label class="form-label d-block invisible">Actualizar</label>
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-arrow-repeat me-1"></i> Actualizar
                    </button>
                </div>
            </div>

            <div class="text-end mt-2">
                <a href="index.php?controller=convocatoria&action=index" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Cancelar
                </a>
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

<!-- BOOTSTRAP BUNDLE -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
