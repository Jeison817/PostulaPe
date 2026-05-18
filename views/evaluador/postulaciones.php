<?php
// Asume que $idConvocatoria, $postulaciones y $_SESSION están definidos en el controlador
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postulaciones de la Convocatoria - PostulaPe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: url("public/img/fondo_interfaz.jpg") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            display: flex;
            flex-direction: column;
        }
        .container {
            max-width: 1600px;
            margin-top: 2rem;
            flex: 1 0 auto;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.95);
            border: none;
            padding: 2rem;
        }
        h3 {
            font-weight: 700;
            font-size: 2rem;
            background: linear-gradient(90deg, #0d6efd, #1e40af);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
            margin-bottom: 1rem;
        }
        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #1a3c34;
            border-left: 4px solid #28a745;
        }
        .alert-success i {
            margin-right: 0.5rem;
        }
        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #4a1c1e;
            border-left: 4px solid #dc3545;
        }
        .alert-danger i {
            margin-right: 0.5rem;
        }
        .form-control {
            padding: 0.75rem;
            font-size: 1rem;
            border-radius: 10px;
            border: 1px solid #ced4da;
            max-width: 350px;
            display: inline-block;
        }
        .form-label {
            font-weight: 600;
            font-size: 1rem;
            margin-right: 1rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #0d6efd;
            color: white;
            border: none;
        }
        .btn-primary:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
        }
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
        }
        .btn-success, .btn-primary.btn-sm, .btn-warning, .btn-danger {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        .btn-success {
            background: #28a745;
            color: white;
            border: none;
        }
        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        }
        .btn-warning {
            background: #ffc107;
            color: #333;
            border: none;
        }
        .btn-warning:hover {
            background: #e0a800;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
        }
        .btn-danger {
            background: #dc3545;
            color: white;
            border: none;
        }
        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        }
        .table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .table thead {
            background: rgba(13, 110, 253, 0.95);
            color: white;
        }
        .table th {
            padding: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        .table td {
            padding: 0.75rem;
            vertical-align: middle;
        }
        .table-hover tbody tr:hover {
            background: rgba(13, 110, 253, 0.05);
        }
        .badge {
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
        }
        .clickable-name {
            cursor: pointer;
            text-decoration: underline;
            color: #0d6efd;
        }
        .clickable-name:hover {
            color: #0b5ed7;
        }
        footer {
            text-align: center;
            padding: 1rem 0;
            background: rgba(255, 255, 255, 0.95);
            color: #333;
            font-size: 0.95rem;
            font-weight: 500;
            margin-top: 2rem;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }
        @media (max-width: 768px) {
            .container {
                margin-top: 1rem;
                padding: 0 1rem;
            }
            .card {
                padding: 1rem;
            }
            h3 {
                font-size: 1.5rem;
            }
            .form-control {
                padding: 0.5rem;
                font-size: 0.9rem;
                max-width: 200px;
            }
            .form-label {
                font-size: 0.9rem;
            }
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.8rem;
            }
            .table th, .table td {
                font-size: 0.85rem;
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h3>Postulaciones de la Convocatoria</h3>

        <!-- Mensajes de éxito o error -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Filtro por Número de Documento -->
        <form method="GET" action="index.php" class="mb-3 d-flex align-items-center gap-2">
            <input type="hidden" name="controller" value="evaluador">
            <input type="hidden" name="action" value="postulacionesPorNumeroDocumento">
            <input type="hidden" name="idConvocatoria" value="<?= htmlspecialchars($idConvocatoria ?? ''); ?>">
            <label for="numeroDocumento" class="form-label">Filtrar por N° de Documento:</label>
            <input type="text" name="numeroDocumento" id="numeroDocumento" class="form-control"
                   value="<?= isset($_GET['numeroDocumento']) ? htmlspecialchars($_GET['numeroDocumento']) : ''; ?>"
                   placeholder="Ingrese número de documento">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="index.php?controller=evaluador&action=verPostulaciones&idConvocatoria=<?= htmlspecialchars($idConvocatoria ?? ''); ?>" 
               class="btn btn-secondary">Limpiar</a>
        </form>

        <!-- Tabla de postulaciones -->
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Postulante</th>
                <th>N° Documento</th>
                <th>Estado</th>
                <th>CV</th>
                <th>Fecha Postulación</th>
                <th>Evaluaciones Registradas</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($postulaciones) && is_array($postulaciones)): ?>
                <?php foreach ($postulaciones as $index => $p): ?>
                    <?php
                    $idPostulacion  = $p['IdPostulacion'] ?? '';
                    $idConvocatoria = $p['IdConvocatoria'] ?? '';
                    $nombre = htmlspecialchars(trim(($p['ApellidoPaterno'] ?? '') . ' ' . ($p['ApellidoMaterno'] ?? '') . ', ' . ($p['Nombre'] ?? '')), ENT_QUOTES, 'UTF-8');
                    $numDoc = htmlspecialchars($p['NumeroDocumento'] ?? '', ENT_QUOTES, 'UTF-8');
                    $estado = htmlspecialchars(ucfirst($p['Estado'] ?? ''), ENT_QUOTES, 'UTF-8');
                    $fecha  = isset($p['FechaCreacion']) ? date('d/m/Y H:i', strtotime($p['FechaCreacion'])) : '';
                    $totalEvaluaciones = $this->model->contarEvaluaciones($idPostulacion) ?? 0;

                    $hrefEvaluar = 'index.php?controller=evaluador&action=evaluarPostulacion&id=' . urlencode($idPostulacion) . '&idConvocatoria=' . urlencode($idConvocatoria);
                    $hrefEvaluaciones = 'index.php?controller=evaluador&action=listarEvaluaciones&id=' . urlencode($idPostulacion) . '&idConvocatoria=' . urlencode($idConvocatoria);
                    $hrefRechazar = 'index.php?controller=evaluador&action=rechazarPostulacion&id=' . urlencode($idPostulacion) . '&idConvocatoria=' . urlencode($idConvocatoria);
                    ?>
                    <tr>
                        <td><?= $index + 1; ?></td>
                        <td>
    <?php
        // Asegurarse que IdPostulante siempre exista
        $idPostulanteSafe = isset($p['IdPostulante']) ? intval($p['IdPostulante']) : 0;
        $idConvocatoriaSafe = isset($idConvocatoria) ? intval($idConvocatoria) : 0;
    ?>
    <a href="index.php?controller=evaluador&action=detallePostulante&idPostulante=<?= intval($p['IdPostulante']) ?>&idConvocatoria=<?= intval($idConvocatoria) ?>" class="clickable-name">
    <?= $nombre; ?>
</a>
    </a>
</td>
                        <td><?= $numDoc; ?></td>
                        <td><span class="badge bg-info"><?= $estado; ?></span></td>
                        <td>
                            <?php if (!empty($p['CVPath'])): ?>
                                <?php
                                $cvBasename = htmlspecialchars(basename($p['CVPath']), ENT_QUOTES, 'UTF-8');
                                $cvUrl = 'Uploads/cv/' . rawurlencode($cvBasename);
                                ?>
                                <a href="<?= $cvUrl; ?>" target="_blank" class="btn btn-sm btn-primary">📄 Ver CV</a>
                            <?php else: ?>
                                No disponible
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($fecha); ?></td>
                        <td><?= $totalEvaluaciones; ?></td>
                        <td class="text-center">
                            <?php if ($totalEvaluaciones < 3 && strtolower($p['Estado']) !== 'descartado' && strtolower($p['Estado']) !== 'seleccionado'): ?>
                                <a href="<?= $hrefEvaluar; ?>" class="btn btn-success btn-sm" title="Evaluar postulación">✏ Evaluar</a>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled>✏ Evaluar</button>
                            <?php endif; ?>

                            <a href="<?= $hrefEvaluaciones; ?>" 
                               class="btn btn-warning btn-sm <?= $totalEvaluaciones == 0 ? 'disabled' : ''; ?>" 
                               title="Ver evaluaciones">📋 Evaluaciones</a>

                            <?php if (strtolower($p['Estado']) !== 'descartado' && strtolower($p['Estado']) !== 'seleccionado'): ?>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rechazarModal<?= $idPostulacion; ?>">
                                    ❌ Rechazar
                                </button>

                                <div class="modal fade" id="rechazarModal<?= $idPostulacion; ?>" tabindex="-1" aria-labelledby="rechazarModalLabel<?= $idPostulacion; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="rechazarModalLabel<?= $idPostulacion; ?>">Confirmar Rechazo</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>
                                                ¿Está seguro de que desea <strong>rechazar</strong> esta postulación?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <a href="<?= $hrefRechazar; ?>" class="btn btn-danger">Rechazar</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">No hay postulaciones disponibles</td></tr>
            <?php endif; ?>
            </tbody>
        </table>

        <div class="mt-4 text-center">
            <a href="index.php?controller=evaluador&action=verConvocatorias" class="btn btn-secondary">
                ⬅ Volver
            </a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>