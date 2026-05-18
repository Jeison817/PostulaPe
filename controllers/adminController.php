<?php
// revisar si es nesesario
require_once __DIR__ . '/../models/Convocatoria.php';
require_once __DIR__ . '/../models/Usuario.php';

class adminController {
    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['IdPerfil'] != 1) {
            header("Location: index.php?controller=login&action=index");
            exit;
        }

        // Creamos a los objetos
        $modelConvocatoria = new Convocatoria();
        $modelUsuariosActivos = new Usuario();
        // usamos sus consultas del models
        $totalConvocatoriasActivas = $modelConvocatoria->contarConvocatoriasActivas();
        $totalUsuariosActivos=$modelUsuariosActivos->contarUsuariosActivos();

        require_once __DIR__ . '/../views/admin/index.php';
    }
}
