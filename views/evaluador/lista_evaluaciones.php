<?php
// Asume que $evaluaciones está definido en tu controlador
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluaciones de la Postulación - PostulaPe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a73e8;
            --secondary-color: #6c757d;
            --background-color: #f8f9fa;
            --card-bg: rgba(255, 255, 255, 0.98);
            --text-color: #1a1a1a;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --border-radius: 12px;
        }

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
            max-width: 1500px; /* Aumentado para acomodar la tabla ancha */
            margin: 2rem auto;
            flex: 1 0 auto;
            padding: 0 1rem;
        }

        .card {
            background: var(--card-bg);
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2rem;
            width: 100%;
        }

        h3 {
            font-weight: 700;
            font-size: 2rem;
            color: var(--text-color);
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        h3::after {
            content: '';
            width: 60px;
            height: 4px;
            background: var(--primary-color);
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .table {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            min-width: 1400px; /* Tabla mucho más ancha */
            width: 100%;
        }

        .table thead {
            background: var(--primary-color);
            color: white;
        }

        .table th {
            padding: 1.25rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .table td {
            padding: 1.25rem;
            vertical-align: middle;
            font-size: 1rem;
        }

        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 10%; /* Columna # */
        }

        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 20%; /* Columna Etapa */
        }

        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 15%; /* Columna Calificación */
        }

        .table th:nth-child(4),
        .table td:nth-child(4) {
            width: 30%; /* Columna Comentario, más ancha */
        }

        .table th:nth-child(5),
        .table td:nth-child(5) {
            width: 15%; /* Columna Resultado */
        }

        .table th:nth-child(6),
        .table td:nth-child(6) {
            width: 10%; /* Columna Acciones */
        }

        .table-hover tbody tr:hover {
            background: rgba(26, 115, 232, 0.05);
        }

        .badge {
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-weight: 500;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .btn-secondary {
            background: var(--secondary-color);
            border: none;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        .btn-warning {
            background: #ffc107;
            color: var(--text-color);
            padding: 0.5rem 1rem; /* Tamaño original de btn-sm */
            font-size: 0.9rem; /* Tamaño original de btn-sm */
            border: none;
        }

        .btn-warning:hover {
            background: #e0a800;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
        }

        footer {
            text-align: center;
            padding: 1.5rem 0;
            background: var(--card-bg);
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 500;
            border-top: 1px solid #e5e7eb;
            box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.05);
            flex-shrink: 0;
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .container {
                margin: 1rem auto;
                padding: 0 0.5rem;
            }

            .card {
                padding: 1.5rem;
                overflow-x: auto; /* Desplazamiento horizontal en móviles */
            }

            h3 {
                font-size: 1.75rem;
            }

            .table th,
            .table td {
                font-size: 0.85rem;
                padding: 0.75rem;
            }

            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .btn-warning {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h3>Evaluaciones de la Postulación</h3>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Etapa</th>
                        <th scope="col">Calificación</th>
                        <th scope="col">Comentario</th>
                        <th scope="col">Resultado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($evaluaciones)): ?>
                        <?php foreach ($evaluaciones as $index => $e): ?>
                            <?php
                                $calificacion = floatval($e['Calificacion']);
                                $resultado = ($calificacion >= 10) ? "Aprobado" : "Desaprobado";
                                $badgeClass = ($calificacion >= 10) ? "success" : "danger";
                            ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($e['Etapa']) ?></td>
                                <td><?= htmlspecialchars($e['Calificacion']) ?></td>
                                <td><?= htmlspecialchars($e['Comentario']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $badgeClass ?>">
                                        <?= $resultado ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="index.php?controller=evaluador&action=editarEvaluacion&id=<?= $e['IdEvaluacion'] ?>" 
                                       class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay evaluaciones</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="mt-4 text-center">
                <a href="index.php?controller=evaluador&action=verPostulaciones&idConvocatoria=<?= $evaluaciones[0]['IdConvocatoria'] ?? '' ?>" 
                   class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>