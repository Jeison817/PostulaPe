<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
    label.form-label {
        font-weight: bold;
    }
    .card-transparent {
        background: rgba(255, 255, 255, 0.85);
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        padding: 20px;
    }
    body, html {
        height: 100%;
        margin: 0;
    }
    .main-container {
        min-height: 100vh;
        padding: 50px 20px;
        background: url('public/img/fondo_interfaz.jpg') no-repeat center center / cover;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .volver-panel {
        margin-bottom: 30px;
        align-self: flex-start;
        padding-left: 20px;
    }
    .cuadros-container {
        max-width: 1200px;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        gap: 1rem;
    }
</style>

<div class="main-container">

    <div class="volver-panel">
        <?php $idConvocatoria = $_GET['idConvocatoria'] ?? ''; ?>
<a href="index.php?controller=evaluador&action=verPostulaciones&idConvocatoria=<?= $idConvocatoria ?>" class="btn btn-secondary">
    &larr; Volver a Postulaciones
</a>
    </div>

    <div class="cuadros-container">
        <!-- Columna de foto -->
        <div class="col-12 col-md-4">
            <div class="card-transparent text-center">
                <h5 class="mb-4 text-success fw-bold">Foto del Postulante</h5>
                <?php if(!empty($postulante['Foto'])): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($postulante['Foto']) ?>" 
                         class="rounded shadow-sm mb-3" style="width: 100%; max-width: 250px; height: 250px; object-fit: cover; border:3px solid #ddd;">
                <?php else: ?>
                    <img src="public/img/default-user.png" 
                         class="rounded shadow-sm mb-3" style="width: 100%; max-width: 250px; height: 250px; object-fit: cover; border:3px solid #ddd;">
                <?php endif; ?>
            </div>
        </div>

        <!-- Columna de datos -->
        <div class="col-12 col-md-8">
            <div class="card-transparent">
                <h3 class="text-success mb-4 fw-bold text-center">Detalle del Postulante</h3>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" value="<?= $postulante['Nombre'] ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" value="<?= $postulante['ApellidoPaterno'] ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" value="<?= $postulante['ApellidoMaterno'] ?>" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tipo de Documento</label>
                        <input type="text" class="form-control" value="<?= $postulante['TipoDocumento'] ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Número de Documento</label>
                        <input type="text" class="form-control" value="<?= $postulante['NumeroDocumento'] ?>" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Género</label>
                        <input type="text" class="form-control" value="<?= $postulante['Genero'] ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado Civil</label>
                        <input type="text" class="form-control" value="<?= $postulante['EstadoCivil'] ?>" readonly>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" value="<?= $postulante['Direccion'] ?>" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Departamento</label>
                        <input type="text" class="form-control" value="<?= $postulante['Departamento'] ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Provincia</label>
                        <input type="text" class="form-control" value="<?= $postulante['Provincia'] ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Distrito</label>
                        <input type="text" class="form-control" value="<?= $postulante['Distrito'] ?>" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" value="<?= $postulante['Telefono'] ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Celular</label>
                        <input type="text" class="form-control" value="<?= $postulante['Celular'] ?>" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>