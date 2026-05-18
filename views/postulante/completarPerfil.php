<div class="d-flex justify-content-center align-items-center vh-100" 
     style="background: url('public/img/fondo-login.png') no-repeat center center/cover;">

  <div class="card shadow-lg p-4" style="max-width: 700px; width: 100%;">
    <h3 class="text-center mb-4 text-success fw-bold">Completa tu Perfil</h3>

    <form method="post" enctype="multipart/form-data" action="index.php?controller=postulante&action=guardarPerfil">
      
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Tipo de Documento</label>
          <select name="tipoDocumento" class="form-select" required>
            <option value="">Selecciona un tipo de documento</option>
            <?php foreach($tipoDocumentos as $td): ?>
              <option value="<?= $td['IdTipoDocumento'] ?>"><?= $td['NombreTipoDocumento'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Número de Documento</label>
          <input type="text" name="numeroDocumento" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Género</label>
          <select name="genero" class="form-select" required>
            <option value="">Selecciona un género</option>
            <?php foreach($generos as $g): ?>
              <option value="<?= $g['IdGenero'] ?>"><?= $g['TipoGenero'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Estado Civil</label>
          <select name="estadoCivil" class="form-select" required>
            <option value="">Selecciona un estado civil</option>
            <?php foreach($estadosCiviles as $ec): ?>
              <option value="<?= $ec['IdEstadoCivil'] ?>"><?= $ec['TipoEstadoCivil'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label">Dirección</label>
          <input type="text" name="direccion" class="form-control" required>
        </div>

        <div class="col-md-4">
          <label class="form-label">Departamento</label>
          <select name="departamento" id="departamento" class="form-select" required>
            <option value="">Selecciona un departamento</option>
            <?php foreach($departamentos as $d): ?>
              <option value="<?= $d['IdDepartamento'] ?>"><?= $d['NombreDepartamento'] ?></option>
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
          <input type="text" name="telefono" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Celular</label>
          <input type="text" name="celular" class="form-control" required>
        </div>

        <div class="col-12">
          <label class="form-label">Foto</label>
          <input type="file" name="foto" class="form-control" id="fotoInput">
          <img id="previewFoto" src="#" alt="Vista previa" class="rounded mt-2 shadow-sm" 
               style="display:none; max-width:150px;">
        </div>
      </div>

      <div class="d-flex justify-content-between mt-4">
        <button type="submit" class="btn btn-success px-4">Guardar Perfil</button>
        <a href="index.php?controller=login&action=logout" class="btn btn-outline-secondary px-4">Salir</a>
      </div>
    </form>
  </div>
</div>

<!-- JS que ya tienes -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#fotoInput').change(function(){
        const file = this.files[0];
        if(file){
            $('#previewFoto').attr('src', URL.createObjectURL(file)).show();
        } else {
            $('#previewFoto').hide();
        }
    });

    $('#departamento').change(function() {
        var idDepartamento = $(this).val();
        $('#provincia').html('<option>Cargando...</option>');
        $('#distrito').html('<option>Selecciona primero la provincia</option>');

        if(idDepartamento != '') {
            $.ajax({
                url: 'index.php',
                type: 'GET',
                data: {
                    controller: 'postulante',
                    action: 'getProvincias',
                    idDepartamento: idDepartamento
                },
                success: function(response) {
                    $('#provincia').html(response);
                }
            });
        } else {
            $('#provincia').html('<option>Selecciona primero el departamento</option>');
        }
    });

    $('#provincia').change(function() {
        var idProvincia = $(this).val();
        $('#distrito').html('<option>Cargando...</option>');

        if(idProvincia != '') {
            $.ajax({
                url: 'index.php',
                type: 'GET',
                data: {
                    controller: 'postulante',
                    action: 'getDistritos',
                    idProvincia: idProvincia
                },
                success: function(response) {
                    $('#distrito').html(response);
                }
            });
        } else {
            $('#distrito').html('<option>Selecciona primero la provincia</option>');
        }
    });
});
</script>
