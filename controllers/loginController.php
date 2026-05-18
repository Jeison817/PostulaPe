<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Postulante.php'; // <- esto faltaba

class loginController {
    public function index() {
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/login/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

public function auth() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario   = $_POST['usuario'] ?? '';
        $password  = $_POST['contrasena'] ?? '';

        $model = new Usuario();
        $user  = $model->login($usuario, $password);

        if ($user) {
            $_SESSION['user'] = $user;

            switch ($user['IdPerfil']) {
                case 1: // Administrador
                    header("Location: index.php?controller=admin&action=index");
                    break;

                case 2: // Postulante
                    $postulanteModel = new Postulante();

                    // Guardar IdPostulante en sesión si existe
                    $idPostulante = $postulanteModel->obtenerIdPostulante($user['IdUsuario']);
                    if ($idPostulante) {
                        $_SESSION['user']['IdPostulante'] = $idPostulante;
                    }

                    // Verificar si es su primer ingreso
                    if ($postulanteModel->esPrimerIngreso($user['IdUsuario'])) {
                        header("Location: index.php?controller=postulante&action=completarPerfil");
                    } else {
                        header("Location: index.php?controller=postulante&action=index");
                    }
                    break;

                case 3: // Evaluador
                    header("Location: index.php?controller=evaluador&action=index");
                    break;

                default:
                    header("Location: index.php?controller=login&action=index");
            }
            exit;
        } else {
            $_SESSION['error'] = "Usuario o contraseña incorrectos.";
            header("Location: index.php?controller=login&action=index");
            exit;
        }
    }
}

        public function register() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $usuario         = $_POST['usuario'] ?? '';
                $contrasena      = $_POST['contrasena'] ?? '';
                $nombre          = $_POST['nombre'] ?? '';
                $apellidoPaterno = $_POST['apellidoPaterno'] ?? '';
                $apellidoMaterno = $_POST['apellidoMaterno'] ?? '';

                $model = new Usuario();
                $registroExitoso = $model->registrarPostulante(
                    $usuario,
                    $contrasena,
                    $nombre,
                    $apellidoPaterno,
                    $apellidoMaterno
                );

                if ($registroExitoso === true) {
                    $_SESSION['success'] = "Usuario creado correctamente. Ahora puedes iniciar sesión.";
                } elseif ($registroExitoso === "existe") {
                    $_SESSION['error'] = "El correo ya está en uso. Intenta con otro.";
                } else {
                    $_SESSION['error'] = "Error al registrar usuario. Intente nuevamente.";
                }

                // Redirigir siempre al formulario de registro para mostrar alertas
                header("Location: index.php?controller=login&action=register");
                exit;

            } else {
                // Mostrar formulario de registro
                require_once __DIR__ . '/../views/layouts/header.php';
                require_once __DIR__ . '/../views/login/register.php';
                require_once __DIR__ . '/../views/layouts/footer.php';
            }
        }

    public function logout() {
        session_destroy();
        header("Location: index.php?controller=login&action=index");
        exit;
    }
}
