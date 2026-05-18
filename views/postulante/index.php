<style>
  body {
    background: url("public/img/fondo_interfaz.jpg") no-repeat center center fixed;
    background-size: cover;
    min-height: 100vh;
    font-family: "Segoe UI", sans-serif;
  }
  .panel-header {
    background: linear-gradient(135deg, #198754, #157347);
    color: #fff;
    padding: 25px;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  }
  .card-summary {
    border-radius: 20px;
    padding: 40px 20px;
    transition: all 0.3s ease;
    background: #fff;
  }
  .card-summary:hover {
    transform: translateY(-8px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
  }
  .card-summary h2 {
    font-size: 3rem;
    font-weight: bold;
  }
  .card-option {
    border-radius: 20px;
    transition: all 0.3s ease;
    cursor: pointer;
    padding: 35px 20px;
    background: #fff;
  }
  .card-option:hover {
    background: #198754;
    color: #fff;
    transform: translateY(-8px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
  }
  .card-option h5 {
    font-size: 1.2rem;
    margin-top: 15px;
  }
  .icon {
    font-size: 2rem;
    display: block;
    margin-bottom: 10px;
  }
</style>

<div class="container-fluid vh-100 d-flex flex-column">

  <!-- HEADER -->
  <div class="panel-header d-flex justify-content-between align-items-center">
    <h2>Panel del Postulante</h2>
    <p class="mb-0">👋 Bienvenido, <strong><?= $_SESSION['user']['Nombre']; ?></strong></p>
    <a href="index.php?controller=login&action=logout" class="btn btn-light btn-sm">
      🔒 Cerrar Sesión
    </a>
  </div>

  <!-- CONTENIDO -->
  <div class="container my-5 flex-grow-1">

    <!-- RESUMEN -->
    <div class="row g-3 text-center mb-5">
      <div class="col-md-4">
        <div class="card card-summary shadow">
          <div class="icon">📋</div>
          <h5>Convocatorias vigentes</h5>
          <h2 class="text-success"><?= $convocatoriasCount ?? 0 ?></h2>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-summary shadow">
          <div class="icon">📂</div>
          <h5>Mis Postulaciones</h5>
          <h2 class="text-primary"><?= $postulacionesCount ?? 0 ?></h2>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-summary shadow">
          <div class="icon">🕒</div>
          <h5>Última postulación</h5>
          <p class="text-muted mb-0 fs-5">
            <?= $ultimaPostulacion ?? "Aún no registras postulaciones" ?>
          </p>
        </div>
      </div>
    </div>

    <!-- OPCIONES -->
    <div class="row g-3 justify-content-center">
      <div class="col-md-4">
        <a href="index.php?controller=postulante&action=editarPerfil" class="text-decoration-none">
          <div class="card card-option shadow text-center">
            <div class="icon">✏️</div>
            <h5>Editar Perfil</h5>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="index.php?controller=postulante&action=convocatorias" class="text-decoration-none">
          <div class="card card-option shadow text-center">
            <div class="icon">📋</div>
            <h5>Ver Convocatorias</h5>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="index.php?controller=postulante&action=postulaciones" class="text-decoration-none">
          <div class="card card-option shadow text-center">
            <div class="icon">📂</div>
            <h5>Mis Postulaciones</h5>
          </div>
        </a>
      </div>
    </div>

  </div>
</div>
