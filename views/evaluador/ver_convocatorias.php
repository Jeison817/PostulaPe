
<?php
// Asume que $areas, $convocatorias y $_SESSION están definidos en tu controlador
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convocatorias Disponibles - PostulaPe</title>
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
            max-width: 1400px;
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
        .form-select {
            padding: 0.75rem;
            font-size: 1rem;
            border-radius: 10px;
            border: 1px solid #ced4da;
            max-width: 300px;
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
        .btn-info {
            background: #17a2b8;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
        }
        .btn-info:hover {
            background: #138496;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(23, 162, 184, 0.4);
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
            .form-select {
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
            <h3>Convocatorias Disponibles</h3>

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

            <!-- Filtro por Área -->
            <form method="GET" action="index.php" class="mb-3 d-flex align-items-center gap-2">
                <input type="hidden" name="controller" value="evaluador">
                <input type="hidden" name="action" value="convocatoriasPorArea">
                <label for="idArea" class="form-label">Filtrar por Área:</label>
                <select name="idArea" id="idArea" class="form-select">
                    <option value="0">-- Todas --</option>
                    <?php if (!empty($areas) && is_array($areas)): ?>
                        <?php foreach ($areas as $area): ?>
                            <option value="<?= htmlspecialchars($area['IdArea']); ?>" 
                                    <?= (isset($_GET['idArea']) && $_GET['idArea'] == $area['IdArea']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($area['NombreArea']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="0" disabled>No hay áreas disponibles</option>
                    <?php endif; ?>
                </select>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>

            <!-- Tabla de convocatorias -->
            <table class="table table-hover table-striped mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Título</th>
                        <th>Área</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($convocatorias) && is_array($convocatorias)): ?>
                        <?php foreach ($convocatorias as $conv): ?>
                            <tr>
                                <td><?= htmlspecialchars($conv['IdConvocatoria']); ?></td>
                                <td><?= htmlspecialchars($conv['Titulo']); ?></td>
                                <td><?= htmlspecialchars($conv['NombreArea']); ?></td>
                                <td><?= htmlspecialchars($conv['FechaInicio']); ?></td>
                                <td><?= htmlspecialchars($conv['FechaFin']); ?></td>
                                <td class="text-center">
                                    <a href="index.php?controller=evaluador&action=verPostulaciones&idConvocatoria=<?= urlencode($conv['IdConvocatoria']); ?>" 
                                       class="btn btn-info btn-sm">
                                       Ver Postulaciones
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">No hay convocatorias disponibles</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="mt-4 text-center">
                <a href="index.php?controller=evaluador&action=index" class="btn btn-secondary">
                    ⬅ Volver al Panel
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
