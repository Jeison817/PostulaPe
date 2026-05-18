<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    body, html {
        height: 100%;
        margin: 0;
        background: url('public/img/fondo_interfaz.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #2e2e2e;
    }

    .container {
        max-width: 1100px;
        margin: 50px auto 50px auto;
        padding: 40px 25px;
        background: rgba(255, 255, 255, 0.85);
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    h3 {
        font-weight: 700;
        color: #28a745;
        text-align: center;
        margin-bottom: 40px;
        text-shadow: 1px 1px 2px rgba(40, 167, 69, 0.3);
    }

    .btn-secondary {
        background-color: #e9f5ec;
        border: 2px solid #28a745;
        color: #28a745;
        transition: background-color 0.3s ease, color 0.3s ease;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 18px;
        display: inline-block;
        margin-bottom: 30px;
    }

    .btn-secondary:hover {
        background-color: #28a745;
        color: #fff;
        border-color: #28a745;
        text-decoration: none;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        justify-content: center;
    }

    .col-md-6 {
        flex: 0 0 48%;
        max-width: 48%;
    }

    .card {
        background: rgba(255, 255, 255, 0.85);
        border-radius: 1rem;
        border: 1.5px solid #28a745;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 25px;
        color: #2e2e2e;
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 30px rgba(40, 167, 69, 0.3);
    }

    .card h5 {
        color: #19692c;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .card p {
        font-size: 0.95rem;
        line-height: 1.4;
        margin-bottom: 10px;
    }

    form label {
        font-weight: 600;
        display: block;
        margin-bottom: 6px;
        color: #19692c;
    }

    form input[type="file"] {
        background: #e6f2e9;
        border-radius: 6px;
        border: 1px solid #28a745;
        color: #19692c;
        padding: 6px 10px;
        width: 100%;
        cursor: pointer;
    }

    form button.btn-success {
        background-color: #28a745;
        border: none;
        padding: 10px 20px;
        font-weight: 700;
        border-radius: 8px;
        transition: background-color 0.3s ease;
        width: 100%;
        color: white;
        margin-top: 10px;
    }

    form button.btn-success:hover {
        background-color: #1e7e34;
    }

    .alert {
        border-radius: 10px;
        font-weight: 600;
        margin-top: 15px;
        padding: 10px 15px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-info {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .alert-warning {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }

</style>

<div class="container">
    <h3>Convocatorias Vigentes</h3>

    <?php if(!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <a href="index.php?controller=postulante&action=index" class="btn btn-secondary">&larr; Volver al Panel</a>

    <!-- 🔽 FILTRO POR ÁREA -->
    <form method="GET" class="mb-4 mt-3">
        <input type="hidden" name="controller" value="postulante">
        <input type="hidden" name="action" value="convocatorias">

        <label for="area">Filtrar por Área:</label>
        <select name="area" id="area" onchange="this.form.submit()" class="form-select w-auto d-inline-block">
            <option value="0">Todas</option>
            <?php foreach ($areas as $a): ?>
                <option value="<?= $a['IdArea'] ?>" <?= ($idArea == $a['IdArea']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($a['Nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
    <!-- 🔼 FIN FILTRO -->

    <div class="row">
        <?php if(!empty($convocatorias)): ?>
            <?php foreach($convocatorias as $c): ?>
                <div class="col-md-6">
                    <div class="card">
                        <h5><?= htmlspecialchars($c['Titulo']) ?></h5>
                        <p><strong>Área:</strong> <?= htmlspecialchars($c['Area']) ?></p>
                        <p><?= nl2br(htmlspecialchars($c['Descripcion'])) ?></p>
                        <p><strong>Fecha Inicio:</strong> <?= $c['FechaInicio'] ?> | <strong>Fecha Fin:</strong> <?= $c['FechaFin'] ?></p>

                        <?php if($c['YaPostulado']): ?>
                            <div class="alert alert-info mt-3">Ya te postulaste</div>
                            <?php if($c['CVPath']): ?>
                                <p><strong>CV adjunto:</strong> <a href="<?= $c['CVPath'] ?>" target="_blank" class="text-success">Ver CV</a></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <form method="post" enctype="multipart/form-data" action="index.php?controller=postulante&action=guardarPostulacion" class="mt-3">
                                <input type="hidden" name="idConvocatoria" value="<?= $c['IdConvocatoria'] ?>">
                                <div class="mb-3">
                                    <label>Adjuntar CV</label>
                                    <input type="file" name="cv" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-success">Postular</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning">No hay convocatorias vigentes.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
