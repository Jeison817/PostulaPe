<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    body, html {
        height: 100%;
        margin: 0;
        background: url('public/img/fondo_interfaz.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .card-custom {
        background: rgba(255, 255, 255, 0.9); 
        border-radius: 15px;
        box-shadow: 0 6px 18px rgba(40, 167, 69, 0.15);
        padding: 30px 25px;
        max-width: 1100px;
        margin: 40px auto;
    }

    h2 {
        color: #28a745;
        font-weight: 700;
        text-align: center;
        margin-bottom: 30px;
        text-shadow: 1px 1px 3px rgba(40,167,69,0.25);
    }

    .detalle p {
        margin-bottom: 15px;
        font-size: 1rem;
        line-height: 1.6;
        color: #2e2e2e;
    }

    .detalle strong {
        color: #19692c;
        font-weight: 600;
    }

    .btn-secondary {
        background-color: #e9f5ec;
        border: 2px solid #28a745;
        color: #28a745;
        font-weight: 600;
        border-radius: 8px;
        padding: 10px 25px;
        transition: background-color 0.3s ease, color 0.3s ease;
        display: inline-block;
        margin-top: 20px;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background-color: #28a745;
        color: white;
        border-color: #28a745;
        text-decoration: none;
    }

</style>

<div class="card-custom">
    <h2><?= htmlspecialchars($convocatoria['Titulo']) ?></h2>

    <div class="detalle">
        <p><strong>Área:</strong> <?= htmlspecialchars($convocatoria['Area']) ?></p>
        <p><strong>Descripción:</strong><br><?= nl2br(htmlspecialchars($convocatoria['Descripcion'])) ?></p>
        <p><strong>Fecha Inicio:</strong> <?= date("d/m/Y", strtotime($convocatoria['FechaInicio'])) ?></p>
        <p><strong>Fecha Fin:</strong> <?= date("d/m/Y", strtotime($convocatoria['FechaFin'])) ?></p>
    </div>

    <div class="text-center">
        <a href="index.php?controller=postulante&action=postulaciones" class="btn btn-secondary">
            ← Volver a Mis Postulaciones
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
