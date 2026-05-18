<?php
// Asume que $evaluacion está definido en tu controlador
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evaluación - PostulaPe</title>
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
            max-width: 1000px; /* Igual que en Evaluar Postulación */
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
            margin: 0 auto;
            max-width: 900px;
        }

        h2 {
            font-weight: 700;
            font-size: 2.25rem;
            color: var(--text-color);
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        h2::after {
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

        .alert {
            border-radius: 8px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            background: rgba(220, 53, 69, 0.08);
            border-left: 5px solid #dc3545;
            font-size: 1rem;
        }

        .alert i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .form-select,
        .form-control,
        textarea {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-select:focus,
        .form-control:focus,
        textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
            outline: none;
        }

        .form-select {
            max-width: 350px;
        }

        .form-control.calificacion {
            max-width: 200px;
        }

        textarea {
            max-width: 600px;
            resize: vertical;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .btn-warning {
            background: #ffc107;
            color: var(--text-color);
            border: none;
        }

        .btn-warning:hover {
            background: #e0a800;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
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
                max-width: 100%;
            }

            h2 {
                font-size: 1.75rem;
            }

            .form-select,
            .form-control.calificacion,
            textarea {
                max-width: 100%;
            }

            .btn {
                padding: 0.6rem 1.2rem;
                font-size: 0.95rem;
            }

            .alert {
                font-size: 0.9rem;
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2><i class="bi bi-pencil me-2"></i>Editar Evaluación</h2>

            <?php if (!$evaluacion): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> Evaluación no encontrada.
                </div>
            <?php else: ?>
                <form action="index.php?controller=evaluador&action=actualizarEvaluacion" method="POST">
                    <input type="hidden" name="IdEvaluacion" value="<?= $evaluacion['IdEvaluacion']; ?>">
                    <input type="hidden" name="IdPostulacion" value="<?= $evaluacion['IdPostulacion']; ?>">
                    <input type="hidden" name="IdConvocatoria" value="<?= htmlspecialchars($evaluacion['IdConvocatoria']); ?>">

                    <div class="mb-4">
                        <label class="form-label">Etapa</label>
                        <select class="form-select" disabled>
                            <option value="<?= $evaluacion['IdEtapa']; ?>" selected>
                                <?= htmlspecialchars($evaluacion['Etapa']); ?>
                            </option>
                        </select>
                        <input type="hidden" name="IdEtapa" value="<?= $evaluacion['IdEtapa']; ?>">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Calificación</label>
                        <input type="number" step="0.01" name="Calificacion" class="form-control calificacion" value="<?= $evaluacion['Calificacion']; ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Comentario</label>
                        <textarea name="Comentario" class="form-control" rows="3" required><?= htmlspecialchars($evaluacion['Comentario']); ?></textarea>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-floppy me-2"></i>Guardar Cambios
                        </button>
                        <a href="index.php?controller=evaluador&action=listarEvaluaciones&id=<?= $evaluacion['IdPostulacion']; ?>&idConvocatoria=<?= $_GET['idConvocatoria'] ?? ''; ?>" 
                           class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>