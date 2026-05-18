<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>PostulaPe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    /* Flexbox para layout con footer sticky */
    body, html {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
    }
    #app {
      flex: 1 0 auto; /* crece para ocupar el espacio disponible */
    }
    footer {
      flex-shrink: 0; /* no se encoge */
    }
  </style>
</head>
<body>

<div id="app">

<?php
// Mostrar alertas con SweetAlert2
if (isset($_SESSION['success'])): ?>
<script>
  Swal.fire({
    icon: 'success',
    title: '¡Éxito!',
    text: '<?= $_SESSION['success']; ?>',
    confirmButtonText: 'Aceptar'
  });
</script>
<?php unset($_SESSION['success']); endif; ?>

<?php
if (isset($_SESSION['error'])): ?>
<script>
  Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: '<?= $_SESSION['error']; ?>',
    confirmButtonText: 'Aceptar'
  });
</script>
<?php unset($_SESSION['error']); endif; ?>
