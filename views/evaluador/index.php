<?php
// Asume que $_SESSION['user']['Nombre'] está definido en tu controlador
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Evaluador - PostulaPe</title>
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
            margin-top: 3rem;
            flex: 1 0 auto;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.95);
            border: none;
            padding: 3rem;
        }
        h2 {
            font-weight: 700;
            font-size: 2.5rem;
            background: linear-gradient(90deg, #0d6efd, #1e40af);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
            margin-bottom: 1rem;
        }
        p {
            font-size: 1.3rem;
            color: #1a202c;
            text-align: center;
        }
        .card-option {
            border-radius: 12px;
            padding: 2.5rem;
            text-align: center;
            background: #f8f9fa;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .card-option:hover {
            background: linear-gradient(135deg, #0d6efd, #1e40af);
            color: white;
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 24px rgba(13, 110, 253, 0.3);
        }
        .card-option i {
            font-size: 3rem;
            margin-bottom: 0.75rem;
            color: #0d6efd;
        }
        .card-option:hover i {
            color: white;
        }
        .card-option h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }
        .btn-logout {
            background: linear-gradient(135deg, #0b5ed7, #1e40af);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-logout:hover {
            background: linear-gradient(135deg, #084298, #1e3a8a);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
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
                margin-top: 1.5rem;
                padding: 0 1rem;
            }
            .card {
                padding: 1.5rem;
            }
            h2 {
                font-size: 1.8rem;
            }
            p {
                font-size: 1rem;
            }
            .card-option {
                padding: 1.5rem;
            }
            .card-option i {
                font-size: 2rem;
            }
            .card-option h5 {
                font-size: 1.2rem;
            }
            .btn-logout {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Panel Evaluador</h2>
            <p>Bienvenido, <strong><?= htmlspecialchars($_SESSION['user']['Nombre']); ?></strong></p>

            <div class="row g-4 mt-3">
                <div class="col-md-4">
                    <a href="index.php?controller=evaluador&action=convocatorias" class="text-decoration-none">
                        <div class="card-option">
                            <i class="bi bi-clipboard-check"></i>
                            <h5>Gestionar Convocatorias</h5>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="index.php?controller=evaluador&action=areas" class="text-decoration-none">
                        <div class="card-option">
                            <i class="bi bi-building"></i>
                            <h5>Gestionar Áreas</h5>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="index.php?controller=evaluador&action=verConvocatorias" class="text-decoration-none">
                        <div class="card-option">
                            <i class="bi bi-people"></i>
                            <h5>Ver Convocatorias y Postulaciones</h5>
                        </div>
                    </a>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="index.php?controller=login&action=logout" class="btn btn-logout">
                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>