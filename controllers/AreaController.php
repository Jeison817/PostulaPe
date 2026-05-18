<?php
// controllers/AreaController.php

require_once __DIR__ . '/../models/Area.php';

class AreaController {
    private $model;

    public function __construct($conn) {
        $this->model = new Area($conn);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Verificar sesión y perfil admin (IdPerfil == 1)
    private function verificarSesion() {
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['IdPerfil']) || $_SESSION['user']['IdPerfil'] != 1) {
            $_SESSION['error'] = "Acceso denegado. Debes iniciar sesión como administrador.";
            header("Location: index.php?controller=login&action=index");
            exit;
        }
    }

    // Panel de CRUD de áreas
    public function index() {
        $this->verificarSesion();

        // Obtenemos todas, incluyendo inactivas
        $areas = $this->model->obtenerTodas(true);
        $success = $_SESSION['success'] ?? null;
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['success'], $_SESSION['error']);

        // Contar áreas activas e inactivas
        $cantidadActivas = $this->model->contarAreasActivas();
        $cantidadInactivas = $this->model->contarAreasInactivas();

        // Enviamos todos al index Areas
        include __DIR__ . '/../views/admin/CrudAreas.php';
    }

    // Mostrar formulario para crear área
    public function crear() {
        $this->verificarSesion();

        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        include __DIR__ . '/../views/admin/CrearArea.php';
    }

    // Guardar nueva área
    public function guardar() {
        $this->verificarSesion();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'Nombre' => trim($_POST['Nombre'] ?? ''),
                'Descripcion' => trim($_POST['Descripcion'] ?? ''),
                'IdUsuarioCreacion' => $_SESSION['user']['IdUsuario'] ?? 1
            ];

            if (empty($data['Nombre'])) {
                $_SESSION['error'] = "El nombre del área es obligatorio.";
                header('Location: index.php?controller=area&action=crear');
                exit;
            }

            $resultado = $this->model->crear($data);
            if ($resultado === true) {
                $_SESSION['success'] = "Área creada correctamente.";
                header('Location: index.php?controller=area&action=index');
                exit;
            } else {
                $_SESSION['error'] = "Error al crear el área.";
                header('Location: index.php?controller=area&action=crear');
                exit;
            }
        }
    }

    // Mostrar formulario para editar área
    public function editar($id) {
        $this->verificarSesion();

        $area = $this->model->obtenerPorId($id);
        if (!$area) {
            $_SESSION['error'] = "Área no encontrada.";
            header('Location: index.php?controller=area&action=index');
            exit;
        }

        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        include __DIR__ . '/../views/admin/EditarArea.php';
    }

    // Actualizar área
    public function actualizar($id) {
        $this->verificarSesion();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'Nombre' => trim($_POST['Nombre'] ?? ''),
                'Descripcion' => trim($_POST['Descripcion'] ?? ''),
                'IdUsuarioModificacion' => $_SESSION['user']['IdUsuario'] ?? 1
            ];

            if (empty($data['Nombre'])) {
                $_SESSION['error'] = "El nombre del área es obligatorio.";
                header("Location: index.php?controller=area&action=editar&id=$id");
                exit;
            }

            $resultado = $this->model->actualizar($id, $data);
            if ($resultado === true) {
                $_SESSION['success'] = "Área actualizada correctamente.";
                header('Location: index.php?controller=area&action=index');
                exit;
            } else {
                $_SESSION['error'] = "Error al actualizar el área.";
                header("Location: index.php?controller=area&action=editar&id=$id");
                exit;
            }
        }
    }

    // Eliminar área (soft delete)
    public function eliminar($id) {
        $this->verificarSesion();

        $idUsuario = $_SESSION['user']['IdUsuario'] ?? 1;
        $resultado = $this->model->eliminar($id, $idUsuario);

        if ($resultado === true) {
            $_SESSION['success'] = "Área eliminada correctamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar el área.";
        }

        header('Location: index.php?controller=area&action=index');
        exit;
    }

    // Reactivar área (soft undelete)
    public function reactivar($id) {
        $this->verificarSesion();

        $idUsuario = $_SESSION['user']['IdUsuario'] ?? 1;
        $resultado = $this->model->reactivar($id, $idUsuario);

        if ($resultado === true) {
            $_SESSION['success'] = "Área reactivada correctamente.";
        } else {
            $_SESSION['error'] = "Error al reactivar el área.";
        }

        header('Location: index.php?controller=area&action=index');
        exit;
    }


    // metodo de historial de estado
    public function estado() {
    $this->verificarSesion();

    // Obtiene las áreas eliminadas y su historial
    $areasEliminadas = $this->model->obtenerHistorialEliminados();

    $success = $_SESSION['success'] ?? null;
    $error = $_SESSION['error'] ?? null;
    unset($_SESSION['success'], $_SESSION['error']);

    include __DIR__ . '/../views/admin/EstadosAreas.php';
}

}
