<div class="d-flex justify-content-center align-items-center vh-100" 
     style="background: url('public/img/fondo-login.png') no-repeat center center/cover;">
  
  <div class="card shadow-lg p-4" 
       style="max-width: 500px; width: 100%; border-radius: 15px; background: rgba(255, 255, 255, 0.92);">
    
    <h3 class="text-center mb-3 text-success fw-bold">Registro de Postulante</h3>
    <p class="text-center text-muted">Crea tu cuenta para postular a convocatorias</p>

    <!-- Alertas -->
    <?php if(isset($_SESSION['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <form method="post" action="index.php?controller=login&action=register">
      <div class="mb-3">
        <label class="form-label fw-bold">Correo electrónico</label>
        <input type="email" name="usuario" class="form-control" placeholder="ejemplo@correo.com" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Contraseña</label>
        <input type="password" name="contrasena" class="form-control" placeholder="********" required>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-bold">Nombre</label>
          <input type="text" name="nombre" class="form-control" placeholder="Tu nombre" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-bold">Apellido Paterno</label>
          <input type="text" name="apellidoPaterno" class="form-control" placeholder="Apellido paterno" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Apellido Materno</label>
        <input type="text" name="apellidoMaterno" class="form-control" placeholder="Apellido materno" required>
      </div>

      <button type="submit" class="btn btn-success w-100 fw-bold">Registrarme</button>

      <!-- Link para volver al login -->
      <div class="text-center mt-3">
        <a href="index.php?controller=login&action=index" class="text-success fw-semibold">
          ¿Ya tienes cuenta? Inicia sesión
        </a>
      </div>
    </form>
  </div>
</div>
