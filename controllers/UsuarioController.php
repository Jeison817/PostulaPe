    <?php
// controllers/UsuarioController.php

require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../libs/fpdf.php';

class PDF extends FPDF {
    protected $logoPath;

    function __construct($logoPath) {
        parent::__construct();
        $this->logoPath = $logoPath;
    }

    function Header() {
        if(file_exists($this->logoPath)) {
            $this->Image($this->logoPath, 10, 6, 30);
        }
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(40);
        $this->Cell(110, 10, utf8_decode('PostulaPe - Registro de Usuarios Eliminados'), 0, 0, 'C');
        $this->Ln(15);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.3);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 9);
        $this->SetTextColor(128);
        $this->Cell(0, 10, utf8_decode('PostulaPe - Página ') . $this->PageNo() . ' | © ' . date('Y'), 0, 0, 'C');
    }
}


class UsuarioController {
    private $model;

    public function __construct($conn) {
        $this->model = new Usuario($conn);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Verificar sesión y perfil administrador
    private function verificarSesion() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['IdPerfil'] != 1) {
            $_SESSION['error'] = "Acceso denegado. Debes iniciar sesión como administrador.";
            header("Location: index.php?controller=login&action=index");
            exit;
        }
    }

    // Vista principal del CRUD de usuarios
        public function index() {
    
        $this->verificarSesion();

        $idUsuarioActual = $_SESSION['user']['IdUsuario'];

        // Ahora excluimos el usuario actual
        $usuarios = $this->model->obtenerTodosExcluyendoActual($idUsuarioActual, false);
        $cantidadActivos = $this->model->contarUsuariosActivos();
        $cantidadInactivos = $this->model->contarUsuariosInactivos();

        $success = $_SESSION['success'] ?? null;
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['success'], $_SESSION['error']);

        include __DIR__ . '/../views/admin/CrudUsuarios.php';
        }

    // Mostrar formulario de creación
    public function crear() {
        $this->verificarSesion();

        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        include __DIR__ . '/../views/admin/CrearUsuario.php';
    }

    // Guardar nuevo usuario
    public function guardar() {
        $this->verificarSesion();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //$usuario = trim($_POST['Usuario'] ?? '');
            // *************************************
            $usuarioBase = trim($_POST['UsuarioBase'] ?? '');
                $usuario = $usuarioBase . '@system.com';
            //---------------------------------------
            $contrasena = $_POST['Contrasena'] ?? '';
            $nombre = trim($_POST['Nombre'] ?? '');
            $apellidoPaterno = trim($_POST['ApellidoPaterno'] ?? '');
            $apellidoMaterno = trim($_POST['ApellidoMaterno'] ?? '');
            $idPerfil = intval($_POST['IdPerfil'] ?? 0);

            // Solo se permiten perfiles Administrador (1) y Evaluador (3)
            if (!in_array($idPerfil, [1, 3])) {
                $_SESSION['error'] = "Solo se permiten perfiles Administrador y Evaluador.";
                header('Location: index.php?controller=usuario&action=crear');
                exit;
            }

            if (empty($usuario) || empty($contrasena)) {
                $_SESSION['error'] = "Usuario y contraseña son obligatorios.";
                header('Location: index.php?controller=usuario&action=crear');
                exit;
            }

            $hash = hash('sha512', $contrasena); // o usar password_hash si prefieres
            $data = [
                'Usuario' => $usuario,
                'ContrasenaHash' => $hash,
                'Nombre' => $nombre,
                'ApellidoPaterno' => $apellidoPaterno,
                'ApellidoMaterno' => $apellidoMaterno,
                'IdPerfil' => $idPerfil,
                'IdUsuarioCreacion' => $_SESSION['user']['IdUsuario']
            ];

            $resultado = $this->model->crear($data);

            if ($resultado === true) {
                $_SESSION['success'] = "Usuario creado correctamente.";
                header('Location: index.php?controller=usuario&action=index');
            } else {
                $_SESSION['error'] = "Error al crear el usuario (¿ya existe?).";
                header('Location: index.php?controller=usuario&action=crear');
            }
            exit;
        }
    }

    // Mostrar formulario de edición
    public function editar($id) {
        $this->verificarSesion();
        $usuario = $this->model->obtenerPorId($id);
        if (!$usuario) {
            $_SESSION['error'] = "Usuario no encontrado.";
            header('Location: index.php?controller=usuario&action=index');
            exit;
        }

        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        include __DIR__ . '/../views/admin/EditarUsuario.php';
    }

    // Actualizar usuario
    public function actualizar($id) {
    $this->verificarSesion();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuarioBase = trim($_POST['UsuarioBase'] ?? '');
        $usuario = $usuarioBase . '@system.com';
        //$usuario = trim($_POST['Usuario'] ?? '');
        $nombre = trim($_POST['Nombre'] ?? '');
        $apellidoPaterno = trim($_POST['ApellidoPaterno'] ?? '');
        $apellidoMaterno = trim($_POST['ApellidoMaterno'] ?? '');
        $idPerfil = intval($_POST['IdPerfil'] ?? 0);

        // Validar campos obligatorios
        if (empty($usuario) || empty($nombre) || empty($apellidoPaterno) || empty($idPerfil)) {
            $_SESSION['error'] = "Nombre, Apellido Paterno, Usuario y Perfil son obligatorios.";
            header("Location: index.php?controller=usuario&action=editar&id=$id");
            exit;
        }

        // Validaciones de formato opcionales
        if (strlen($usuario) < 4 || preg_match('/\s/', $usuario)) {
            $_SESSION['error'] = "El nombre de usuario debe tener al menos 4 caracteres y sin espacios.";
            header("Location: index.php?controller=usuario&action=editar&id=$id");
            exit;
        }

        if (!in_array($idPerfil, [1, 3])) {
            $_SESSION['error'] = "Solo se permiten perfiles Administrador y Evaluador.";
            header("Location: index.php?controller=usuario&action=editar&id=$id");
            exit;
        }

        $data = [
            'Usuario' => $usuario,
            'Nombre' => $nombre,
            'ApellidoPaterno' => $apellidoPaterno,
            'ApellidoMaterno' => $apellidoMaterno,
            'IdPerfil' => $idPerfil,
            'IdUsuarioModificacion' => $_SESSION['user']['IdUsuario']
        ];

        // Si quieres usar contraseña, aquí la manejarías
        // $contrasena = $_POST['Contrasena'] ?? '';
        // if (!empty($contrasena)) {
        //     $data['ContrasenaHash'] = hash('sha512', $contrasena);
        // }

        $resultado = $this->model->actualizar($id, $data);

        if ($resultado === true) {
            $_SESSION['success'] = "Usuario actualizado correctamente.";
            header('Location: index.php?controller=usuario&action=index');
        } else {
            $_SESSION['error'] = "Error al actualizar el usuario.";
            header("Location: index.php?controller=usuario&action=editar&id=$id");
        }
        exit;
    }
}
    
    // Mostrar Usuarios Eliminados
    public function obtenerHistorialEliminados() {
    $this->verificarSesion();

    $usuarios = $this->model->obtenerHistorialEliminados();
    $success = $_SESSION['success'] ?? null;
    $error = $_SESSION['error'] ?? null;
    unset($_SESSION['success'], $_SESSION['error']);

    include __DIR__ . '/../views/admin/CrudUsuariosEliminados.php';
    }


    // Eliminar (soft delete)
    public function eliminar($id) {
        $this->verificarSesion();

        $idUsuario = $_SESSION['user']['IdUsuario'];
        $resultado = $this->model->eliminar($id, $idUsuario);

        $_SESSION[$resultado === true ? 'success' : 'error'] = $resultado === true 
            ? "Usuario eliminado correctamente." 
            : "Error al eliminar el usuario.";

        header('Location: index.php?controller=usuario&action=index');
        exit;
    }

    // Reactivar
    public function reactivar($id) {
        $this->verificarSesion();

        $idUsuario = $_SESSION['user']['IdUsuario'];
        $resultado = $this->model->reactivar($id, $idUsuario);

        $_SESSION[$resultado === true ? 'success' : 'error'] = $resultado === true 
            ? "Usuario reactivado correctamente." 
            : "Error al reactivar el usuario.";

        header('Location: index.php?controller=usuario&action=index');
        exit;
    }

    // Mostrar historial de usuarios eliminados
    public function estado() {
        $this->verificarSesion();

        $usuariosEliminados = $this->model->obtenerHistorialEliminados();

        $success = $_SESSION['success'] ?? null;
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['success'], $_SESSION['error']);

        include __DIR__ . '/../views/admin/EstadosUsuarios.php';
    }



    // ************************************
    public function exportarPdf() {
    $this->verificarSesion();

    // Obtener usuarios eliminados
    $usuarios = $this->model->obtenerHistorialEliminados();

    $logoPath = __DIR__ . '/../libs/sise-logo.png';

    $pdf = new PDF($logoPath);
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(true, 25);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 8, utf8_decode('Fecha de generación: ') . date('d/m/Y H:i'), 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', '', 11);
    $introText = "Este documento contiene el registro detallado de los usuarios que han sido desactivados o eliminados del sistema PostulaPe. "
        . "Este reporte tiene como objetivo ofrecer un seguimiento claro y transparente de los cambios en el estado de los usuarios, facilitando la auditoría y el control interno.";
    $pdf->MultiCell(0, 6, utf8_decode($introText));
    $pdf->Ln(8);

    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetFillColor(200, 200, 200);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(15, 10, 'ID', 1, 0, 'C', true);
    $pdf->Cell(45, 10, 'Usuario', 1, 0, 'C', true);
    $pdf->Cell(55, 10, utf8_decode('Nombre Completo'), 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Perfil', 1, 0, 'C', true);
    $pdf->Cell(35, 10, utf8_decode('Fecha Eliminación'), 1, 0, 'C', true);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $fill = false;

    foreach ($usuarios as $usuario) {
        if($pdf->GetY() > 260) {
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(15, 10, 'ID', 1, 0, 'C', true);
            $pdf->Cell(45, 10, 'Usuario', 1, 0, 'C', true);
            $pdf->Cell(55, 10, utf8_decode('Nombre Completo'), 1, 0, 'C', true);
            $pdf->Cell(30, 10, 'Perfil', 1, 0, 'C', true);
            $pdf->Cell(35, 10, utf8_decode('Fecha Eliminación'), 1, 0, 'C', true);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 10);
        }

        $pdf->SetFillColor($fill ? 240 : 255);
        $pdf->SetTextColor(0, 0, 0);

        $nombreCompleto = $usuario['Nombre'] . ' ' . $usuario['ApellidoPaterno'] . ' ' . $usuario['ApellidoMaterno'];

        switch ($usuario['IdPerfil']) {
            case 1:
                $perfil = 'Administrador';
                break;
            case 3:
                $perfil = 'Evaluador';
                break;
            default:
                $perfil = 'Postulante';
        }

        $pdf->Cell(15, 10, $usuario['IdUsuario'], 1, 0, 'C', $fill);
        $pdf->Cell(45, 10, utf8_decode($usuario['Usuario']), 1, 0, 'L', $fill);
        $pdf->Cell(55, 10, utf8_decode($nombreCompleto), 1, 0, 'L', $fill);
        $pdf->Cell(30, 10, $perfil, 1, 0, 'C', $fill);
        $pdf->Cell(35, 10, $usuario['FechaEliminado'] ?? '-', 1, 0, 'C', $fill);
        $pdf->Ln();

        $fill = !$fill;
    }

    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'I', 11);
    $pdf->Cell(0, 8, utf8_decode('--- Fin del reporte ---'), 0, 1, 'C');

    $pdf->Output('D', 'usuarios_eliminados_postulape.pdf');
    exit;
}


}
