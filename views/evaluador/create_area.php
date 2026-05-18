
<?php
// Asume que $_SESSION está definido en tu controlador
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Área - PostulaPe</title>
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
            max-width: 800px;
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

        .form-control,
        textarea {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus,
        textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
            outline: none;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
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
            }

            h3 {
                font-size: 1.75rem;
            }

            .form-control,
            textarea {
                font-size: 0.95rem;
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
            <h3>Nueva Área</h3>

            <!-- Mensajes de éxito o error -->
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?controller=evaluador&action=createArea" method="POST">
                <div class="mb-4">
                    <label for="nombre" class="form-label">Nombre Área</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           value="<?= isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '' ?>" required>
                </div>

                <div class="mb-4">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion"><?= isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : '' ?></textarea>
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="index.php?controller=evaluador&action=areas" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
