<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Convocatoria - PostulaPe</title>
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
            max-width: 1000px;
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
        .form-control, .form-select {
            padding: 1rem;
            font-size: 1rem;
            border-radius: 10px;
            border: 1px solid #ced4da;
        }
        .form-label {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }
        .date-input {
    max-width: 180px; /* ligeramente más grande si quieres */
    margin: 0 auto;
}
        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .btn-success {
            background: linear-gradient(135deg, #28a745, #218838);
            color: white;
            border: none;
        }
        .btn-success:hover {
            background: linear-gradient(135deg, #218838, #1e7e34);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
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
            .form-control, .form-select, .date-input {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
            .form-label {
                font-size: 0.9rem;
            }
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }
            .row .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h3>Nueva Convocatoria</h3>

            <!-- Mensajes de éxito o error -->
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php elseif(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?controller=evaluador&action=createConvocatoria" method="POST">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Nombre Convocatoria</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" 
                           value="<?= isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : '' ?>" required>
                </div>

                <div class="mb-3">
                    <label for="idArea" class="form-label">Área</label>
                    <select name="idArea" class="form-select" required>
                        <option value="">-- Seleccionar Área --</option>
                        <?php foreach ($areas as $area): ?>
                            <option value="<?= $area['IdArea']; ?>" 
                                    <?= (isset($_POST['idArea']) && $_POST['idArea'] == $area['IdArea']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($area['NombreArea']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion"><?= isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : '' ?></textarea>
                </div>

              <div class="mb-3 row justify-content-center">
                    <div class="col-auto text-center">
                        <label for="fechaInicio" class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control date-input" id="fechaInicio" name="fechaInicio" 
                            value="<?= isset($_POST['fechaInicio']) ? $_POST['fechaInicio'] : '' ?>" required>
                    </div>
                    <div class="col-auto text-center">
                        <label for="fechaFin" class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control date-input" id="fechaFin" name="fechaFin" 
                            value="<?= isset($_POST['fechaFin']) ? $_POST['fechaFin'] : '' ?>" required>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="index.php?controller=evaluador&action=convocatorias" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
