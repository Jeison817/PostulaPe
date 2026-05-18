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
        <a href="index.php?controller=postulante&action=index" class="btn btn-secondary">
            &larr; Volver al Panel
        </a>
    </div>

    <div class="cuadros-container">
        <form method="post" enctype="multipart/form-data" action="index.php?controller=postulante&action=guardarEdicionPerfil" class="row g-3 w-100">

            <!-- Columna de foto -->
            <div class="col-12 col-md-4">
                <div class="card-transparent text-center">
                    <h5 class="mb-4 text-success fw-bold">Foto de Perfil</h5>
                    <?php if(!empty($perfil['Foto'])): ?>
                        <img id="previewFoto" src="data:image/jpeg;base64,<?= base64_encode($perfil['Foto']) ?>" 
                             class="rounded shadow-sm mb-3" style="width: 100%; max-width: 250px; height: 250px; object-fit: cover; border:3px solid #ddd;">
                    <?php else: ?>
                        <img id="previewFoto" src="#" 
                             class="rounded shadow-sm mb-3" style="display:none; width: 100%; max-width: 250px; height: 250px; object-fit: cover; border:3px solid #ddd;">
                    <?php endif; ?>
                    <input type="file" name="foto" class="form-control form-control-sm mt-3" id="fotoInput">
                </div>
            </div>

            <!-- Columna de formulario -->
            <div class="col-12 col-md-8">
                <div class="card-transparent">
                    <h3 class="text-success mb-4 fw-bold text-center">Editar Perfil</h3>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Usuario</label>
                            <input type="text" class="form-control" value="<?= $_SESSION['user']['Usuario'] ?? '' ?>" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="<?= $perfil['Nombre'] ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellido Paterno</label>
                            <input type="text" name="apellidoPaterno" class="form-control" value="<?= $perfil['ApellidoPaterno'] ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellido Materno</label>
                            <input type="text" name="apellidoMaterno" class="form-control" value="<?= $perfil['ApellidoMaterno'] ?>" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Contraseña <small class="text-muted">(dejar vacío si no desea cambiar)</small></label>
                            <input type="password" name="contrasena" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tipo de Documento</label>
                            <select name="tipoDocumento" class="form-select" required>
                                <option value="">Selecciona...</option>
                                <?php foreach($tipoDocumentos as $td): ?>
                                    <option value="<?= $td['IdTipoDocumento'] ?>" <?= ($perfil['IdTipoDocumento'] == $td['IdTipoDocumento']) ? 'selected' : '' ?>>
                                        <?= $td['NombreTipoDocumento'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Número de Documento</label>
                            <input type="text" name="numeroDocumento" class="form-control" value="<?= $perfil['NumeroDocumento'] ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Género</label>
                            <select name="genero" class="form-select" required>
                                <option value="">Selecciona...</option>
                                <?php foreach($generos as $g): ?>
                                    <option value="<?= $g['IdGenero'] ?>" <?= ($perfil['IdGenero'] == $g['IdGenero']) ? 'selected' : '' ?>>
                                        <?= $g['TipoGenero'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado Civil</label>
                            <select name="estadoCivil" class="form-select" required>
                                <option value="">Selecciona...</option>
                                <?php foreach($estadosCiviles as $ec): ?>
                                    <option value="<?= $ec['IdEstadoCivil'] ?>" <?= ($perfil['IdEstadoCivil'] == $ec['IdEstadoCivil']) ? 'selected' : '' ?>>
                                        <?= $ec['TipoEstadoCivil'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control" value="<?= $perfil['Direccion'] ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Departamento</label>
                            <select name="departamento" id="departamento" class="form-select" required>
                                <option value="">Selecciona un departamento</option>
                                <?php foreach($departamentos as $d): ?>
                                    <option value="<?= $d['IdDepartamento'] ?>" <?= ($perfil['IdDepartamento'] == $d['IdDepartamento']) ? 'selected' : '' ?>>
                                        <?= $d['NombreDepartamento'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Provincia</label>
                            <select name="provincia" id="provincia" class="form-select" required>
                                <option value="">Selecciona primero el departamento</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Distrito</label>
                            <select name="distrito" id="distrito" class="form-select" required>
                                <option value="">Selecciona primero la provincia</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="<?= $perfil['Telefono'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Celular</label>
                            <input type="text" name="celular" class="form-control" value="<?= $perfil['Celular'] ?>" required>
                        </div>

                        <div class="col-12 d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-success px-4">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#fotoInput').change(function(){
        const file = this.files[0];
        if(file){
            $('#previewFoto').attr('src', URL.createObjectURL(file)).show();
        }
    });

    function cargarProvincias(idDepartamento, selectedProvincia='', selectedDistrito=''){
        if(idDepartamento != ''){
            $.get('index.php', {controller:'postulante', action:'getProvincias', idDepartamento: idDepartamento}, function(response){
                $('#provincia').html(response);
                if(selectedProvincia) {
                    $('#provincia').val(selectedProvincia).change();
                    cargarDistritos(selectedProvincia, selectedDistrito);
                }
            });
        } else {
            $('#provincia').html('<option>Selecciona primero el departamento</option>');
            $('#distrito').html('<option>Selecciona primero la provincia</option>');
        }
    }

    function cargarDistritos(idProvincia, selectedDistrito=''){
        if(idProvincia != ''){
            $.get('index.php', {controller:'postulante', action:'getDistritos', idProvincia: idProvincia}, function(response){
                $('#distrito').html(response);
                if(selectedDistrito) $('#distrito').val(selectedDistrito);
            });
        } else {
            $('#distrito').html('<option>Selecciona primero la provincia</option>');
        }
    }

    var idDepartamento = '<?= $perfil['IdDepartamento'] ?>';
    var idProvincia   = '<?= $perfil['IdProvincia'] ?>';
    var idDistrito    = '<?= $perfil['IdDistrito'] ?>';

    if(idDepartamento) cargarProvincias(idDepartamento, idProvincia, idDistrito);

    $('#departamento').change(function(){
        cargarProvincias($(this).val());
        $('#distrito').html('<option>Selecciona primero la provincia</option>');
    });
    $('#provincia').change(function(){
        cargarDistritos($(this).val());
    });
});
</script>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
