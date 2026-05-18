
<?php
// Asume que $areas y $_SESSION están definidos en tu controlador
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Áreas - Panel de Evaluador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: url("public/img/fondo_interfaz.jpg") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            display: flex;
            flex-direction: column;
        }
        .container {
            max-width: 1400px;
            margin-top: 2rem;
            flex: 1 0 auto;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.95);
            border: none;
            padding: 1.5rem;
        }
        h2 {
            color: #0d6efd;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
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
        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #0d6efd;
            border: none;
        }
        .btn-primary:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #6c757d;
            border: none;
        }
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        .btn-warning {
            background: #ffc107;
            color: #333;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        .btn-warning:hover {
            background: #e0a800;
            transform: translateY(-2px);
        }
        .btn-danger {
            background: #dc3545;
            color: white;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
        }
        .table-container {
            overflow-x: auto;
        }
        .table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }
        .table thead {
            background: rgba(13, 110, 253, 0.95);
            color: white;
        }
        .table th {
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        .table td {
            padding: 1rem;
            vertical-align: middle;
        }
        .table-hover tbody tr:hover {
            background: rgba(13, 110, 253, 0.05);
        }
        .descripcion {
            max-width: 400px;
            white-space: normal;
            word-wrap: break-word;
        }
        footer {
            text-align: center;
            padding: 1rem 0;
            background: rgba(13, 110, 253, 0.95);
            color: white;
            font-size: 0.9rem;
            margin-top: 2rem;
            flex-shrink: 0;
        }
        @media (max-width: 768px) {
            .container { margin-top: 1rem; padding: 0 1rem; }
            .table th, .table td { font-size: 0.85rem; padding: 0.75rem; }
            .btn { padding: 0.5rem 1rem; font-size: 0.9rem; }
            .descripcion { max-width: 200px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-buildings-fill me-2"></i>Áreas</h2>
                <a href="index.php?controller=evaluador&action=createArea" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Nueva Área
                </a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php elseif (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Descripción</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($areas)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No hay áreas disponibles.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($areas as $area): ?>
                                <tr>
                                    <td><?= htmlspecialchars($area['IdArea']); ?></td>
                                    <td><strong><?= htmlspecialchars($area['Nombre']); ?></strong></td>
                                    <td class="descripcion"><?= htmlspecialchars($area['Descripcion']); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="index.php?controller=evaluador&action=editArea&id=<?= $area['IdArea']; ?>" 
                                               class="btn btn-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $area['IdArea']; ?>" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="deleteModal<?= $area['IdArea']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $area['IdArea']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel<?= $area['IdArea']; ?>">Confirmar Eliminación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Seguro que deseas eliminar esta área?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <a href="index.php?controller=evaluador&action=deleteArea&id=<?= $area['IdArea']; ?>" class="btn btn-danger">Eliminar</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-center">
                <a href="index.php?controller=evaluador&action=index" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver al Panel
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
