<div class="d-flex justify-content-center align-items-center vh-100" 
     style="background: url('public/img/fondo-login.png') no-repeat center center/cover; position: relative;">
  
  <!-- Capa oscura para mejorar contraste -->
  <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.6);"></div>

  <div class="card shadow-lg p-4" 
       style="max-width: 420px; width: 100%; position: relative; z-index: 2; border-radius: 15px; background: rgba(255, 255, 255, 0.9);">
    
    <h3 class="text-center mb-3 text-success fw-bold">PostulaPe</h3>
    <p class="text-center text-muted">Bienvenido, inicia sesión para continuar</p>

    <!-- Mostrar alertas de error -->
    <?php if(isset($_SESSION['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <!-- Mostrar alerta de éxito tras registro -->
    <?php if(isset($_GET['registro']) && $_GET['registro'] === 'ok'): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        Registro exitoso. Ahora puedes iniciar sesión.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <form method="post" action="index.php?controller=login&action=auth">
      <div class="mb-3">
        <label class="form-label fw-bold">Usuario (correo)</label>
        <input type="email" name="usuario" class="form-control" placeholder="ejemplo@correo.com" required>
      </div>
      <div class="mb-3">
        <label class="form-label fw-bold">Contraseña</label>
        <input type="password" name="contrasena" class="form-control" placeholder="********" required>
      </div>
      <button type="submit" class="btn btn-success w-100 fw-bold">Entrar</button>
    </form>

    <!-- Link al registro -->
    <div class="text-center mt-3">
      <a href="index.php?controller=login&action=register" class="text-success fw-semibold">
        ¿No tienes cuenta? Regístrate aquí
      </a>
    </div>
  </div>
</div>
