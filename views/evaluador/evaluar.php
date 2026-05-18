<?php
// Asume que $postulacion y $etapas están definidos en tu controlador
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluar Postulación - PostulaPe</title>
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
            max-width: 1000px;
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

        .badge {
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-weight: 500;
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

        .form-control.comment {
            max-width: 600px;
            resize: vertical;
        }

        strong {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-color);
            display: block;
            margin-bottom: 0.75rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .btn-success {
            background: var(--primary-color);
            border: none;
            color: white;
        }

        .btn-success:hover {
            background: #1557b0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);
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
            .form-control.comment {
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
            <h2>Evaluar Postulación</h2>

            <?php if (!$postulacion): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> No se encontró la postulación o el ID no es válido.
                </div>
            <?php else: ?>
                <p><strong>Postulante:</strong> <?= htmlspecialchars($postulacion['ApellidoPaterno'].' '.$postulacion['ApellidoMaterno'].', '.$postulacion['Nombre']); ?></p>
                <p><strong>Estado actual:</strong> <span class="badge bg-warning"><?= htmlspecialchars($postulacion['Estado']); ?></span></p>

                <form action="index.php?controller=evaluador&action=guardarEvaluacion" method="POST">
                    <input type="hidden" name="IdPostulacion" value="<?= $postulacion['IdPostulacion']; ?>">
                    <input type="hidden" name="IdConvocatoria" value="<?= htmlspecialchars($postulacion['IdConvocatoria']); ?>">

                    <div class="mb-4">
                        <label class="form-label">Etapa</label>
                        <select name="IdEtapa" class="form-select" required>
                            <option value="">>Seleccione una etapa<</option>
                            <?php foreach($etapas as $et): ?>
                                <option value="<?= $et['IdEtapa']; ?>"><?= htmlspecialchars($et['Nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Calificación</label>
                        <input type="number" step="0.01" name="Calificacion" class="form-control calificacion" maxlength="5" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Comentario</label>
                        <textarea name="Comentario" class="form-control comment" rows="3" required></textarea>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <button type="submit" class="btn btn-success">Guardar Evaluación</button>
                        <a href="index.php?controller=evaluador&action=verPostulaciones&idConvocatoria=<?= htmlspecialchars($postulacion['IdConvocatoria']); ?>" 
                           class="btn btn-secondary">Volver</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>