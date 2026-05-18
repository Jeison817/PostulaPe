<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container mt-5">
    <div class="mb-3">
        <a href="index.php?controller=postulante&action=verConvocatorias" class="btn btn-secondary">Volver a Convocatorias</a>
    </div>

    <h3>Postular a Convocatoria: <?= $convocatoria['Titulo'] ?></h3>
    <form method="post" enctype="multipart/form-data" action="index.php?controller=postulante&action=guardarPostulacion">
        <input type="hidden" name="idConvocatoria" value="<?= $convocatoria['IdConvocatoria'] ?>">

        <div class="mb-3">
            <label>Adjuntar CV</label>
            <input type="file" name="cv" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Enviar Postulación</button>
    </form>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
