<?php
require_once __DIR__ . '/../models/Postulante.php';
require_once __DIR__ . '/../models/Catalogo.php';

class postulanteController {

    // Página principal del postulante (después de completar perfil)
        public function index() {
            if (!isset($_SESSION['user'])) {
                header("Location: index.php?controller=login&action=index");
                exit;
            }

            $postulanteModel = new Postulante();

            // Recuperamos el IdPostulante (cuando completó perfil lo guardaste en sesión)
            $idPostulante = $_SESSION['user']['IdPostulante'] ?? null;

            if (!$idPostulante) {
                $_SESSION['error'] = "No se encontró información del postulante.";
                header("Location: index.php?controller=postulante&action=completarPerfil");
                exit;
            }

            // 🔹 Llamamos al modelo que trae los datos desde el SP
            $resumen = $postulanteModel->obtenerResumen($idPostulante);

            // Variables para la vista
            $convocatoriasCount = $resumen['convocatoriasCount'] ?? 0;
            $postulacionesCount = $resumen['postulacionesCount'] ?? 0;
            $ultimaPostulacion  = $resumen['ultimaPostulacion'] ?? "Aún no registras postulaciones";

            // Cargar vista con datos
            require_once __DIR__ . '/../views/layouts/header.php';
            require_once __DIR__ . '/../views/postulante/index.php';
            require_once __DIR__ . '/../views/layouts/footer.php';
        }

    // Formulario para completar perfil (primer login)
    public function completarPerfil() {
        $catalogo = new Catalogo();

        // Traemos los catálogos para los selects
        $departamentos   = $catalogo->getDepartamentos();
        $tipoDocumentos  = $catalogo->getTiposDocumento();
        $generos         = $catalogo->getGeneros();
        $estadosCiviles  = $catalogo->getEstadosCiviles();

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/postulante/completarPerfil.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    // Guardar datos del perfil completado
        public function guardarPerfil() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'IdUsuario'       => $_SESSION['user']['IdUsuario'],
                    'IdTipoDocumento' => $_POST['tipoDocumento'] ?? null,
                    'NumeroDocumento' => $_POST['numeroDocumento'] ?? '',
                    'Nombre'          => $_POST['nombre'] ?? '',
                    'ApellidoPaterno' => $_POST['apellidoPaterno'] ?? '',
                    'ApellidoMaterno' => $_POST['apellidoMaterno'] ?? '',
                    'IdGenero'        => $_POST['genero'] ?? null,
                    'IdEstadoCivil'   => $_POST['estadoCivil'] ?? null,
                    'Direccion'       => $_POST['direccion'] ?? '',
                    'IdDepartamento'  => $_POST['departamento'] ?? null,
                    'IdProvincia'     => $_POST['provincia'] ?? null,
                    'IdDistrito'      => $_POST['distrito'] ?? null,
                    'Telefono'        => $_POST['telefono'] ?? '',
                    'Celular'         => $_POST['celular'] ?? '',
                    'Foto'            => $_FILES['foto'] ?? null
                ];

                $postulanteModel = new Postulante();
                $resultado = $postulanteModel->guardarPerfil($data);

                if ($resultado === true) {
                    // ✅ Recuperamos el IdPostulante recién creado
                    $idPostulante = $postulanteModel->obtenerIdPostulante($_SESSION['user']['IdUsuario']);
                    if ($idPostulante) {
                        $_SESSION['user']['IdPostulante'] = $idPostulante;
                    }

                    $_SESSION['success'] = "Perfil completado correctamente.";
                    header("Location: index.php?controller=postulante&action=index");
                    exit;
                } else {
                    $_SESSION['error'] = $resultado;
                    header("Location: index.php?controller=postulante&action=completarPerfil");
                    exit;
                }
            }
        }

    // Devuelve opciones <option> para provincias
        public function getProvincias() {
            $idDepartamento = $_GET['idDepartamento'] ?? '';
            $catalogo = new Catalogo();
            $provincias = $catalogo->getProvincias($idDepartamento);

            echo '<option value="">Selecciona una provincia</option>';
            foreach($provincias as $prov) {
                echo "<option value='{$prov['IdProvincia']}'>{$prov['NombreProvincia']}</option>";
            }
        }

        // Devuelve opciones <option> para distritos
        public function getDistritos() {
            $idProvincia = $_GET['idProvincia'] ?? '';
            $catalogo = new Catalogo();
            $distritos = $catalogo->getDistritos($idProvincia);

            echo '<option value="">Selecciona un distrito</option>';
            foreach($distritos as $dist) {
                echo "<option value='{$dist['IdDistrito']}'>{$dist['NombreDistrito']}</option>";
            }
        }

        // Formulario editar perfil
        public function editarPerfil() {
            $postulante = new Postulante();
            $catalogo   = new Catalogo();

            $perfil = $postulante->obtenerPerfil($_SESSION['user']['IdUsuario']);

            $departamentos   = $catalogo->getDepartamentos();
            $tipoDocumentos  = $catalogo->getTiposDocumento();
            $generos         = $catalogo->getGeneros();
            $estadosCiviles  = $catalogo->getEstadosCiviles();

            require_once __DIR__ . '/../views/layouts/header.php';
            require_once __DIR__ . '/../views/postulante/editarPerfil.php';
            require_once __DIR__ . '/../views/layouts/footer.php';
        }

        // Guardar cambios del perfil
  
    public function guardarEdicionPerfil() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Tomamos la contraseña tal cual la ingresó el usuario (texto plano)
            $contrasena = trim($_POST['contrasena'] ?? ''); // si está vacía, SP mantiene la actual

            // Manejo de foto
            $foto = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
                $foto = $_FILES['foto'];
            }

            $data = [
                'IdUsuario'       => $_SESSION['user']['IdUsuario'],
                'Nombre'          => $_POST['nombre'] ?? '',
                'ApellidoPaterno' => $_POST['apellidoPaterno'] ?? '',
                'ApellidoMaterno' => $_POST['apellidoMaterno'] ?? '',
                'Contrasena'      => $contrasena, // texto plano
                'IdTipoDocumento' => $_POST['tipoDocumento'] ?? null,
                'NumeroDocumento' => $_POST['numeroDocumento'] ?? '',
                'IdGenero'        => $_POST['genero'] ?? null,
                'IdEstadoCivil'   => $_POST['estadoCivil'] ?? null,
                'Direccion'       => $_POST['direccion'] ?? '',
                'IdDepartamento'  => $_POST['departamento'] ?? null,
                'IdProvincia'     => $_POST['provincia'] ?? null,
                'IdDistrito'      => $_POST['distrito'] ?? null,
                'Telefono'        => $_POST['telefono'] ?? '',
                'Celular'         => $_POST['celular'] ?? '',
                'Foto'            => $foto
            ];

            $postulante = new Postulante();
            $resultado = $postulante->actualizarPerfil($data);

            if ($resultado === true) {
                $_SESSION['success'] = "Perfil actualizado correctamente.";
                header("Location: index.php?controller=postulante&action=index");
            } else {
                $_SESSION['error'] = $resultado ?: "Error al actualizar el perfil.";
                header("Location: index.php?controller=postulante&action=editarPerfil");
            }
            exit;
        }
    }
            public function convocatorias() {
                $idPostulante = $_SESSION['user']['IdPostulante'] ?? null;

                if (!$idPostulante) {
                    $_SESSION['error'] = "Error: No se identificó al postulante.";
                    header("Location: index.php?controller=postulante&action=index");
                    exit;
                }

                $postulanteModel = new Postulante();
                $catalogoModel   = new Catalogo();

                // Capturar área seleccionada (si no, 0 para traer todo)
                $idArea = isset($_GET['area']) ? intval($_GET['area']) : 0;

                // Traer convocatorias con filtro
                $convocatorias = $postulanteModel->listarConvocatoriasVigentes($idPostulante, $idArea);

                // Traer todas las áreas para el select
                $areas = $catalogoModel->getAreas();

                require_once __DIR__ . '/../views/layouts/header.php';
                require_once __DIR__ . '/../views/postulante/convocatorias.php';
                require_once __DIR__ . '/../views/layouts/footer.php';
            }

        public function guardarPostulacion() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $idConvocatoria = $_POST['idConvocatoria'] ?? 0;
                $idPostulante   = $_SESSION['user']['IdPostulante'] ?? null;

                if (!$idPostulante) {
                    $_SESSION['error'] = "Error: No se identificó al postulante.";
                    header("Location: index.php?controller=postulante&action=index");
                    exit;
                }

                // Validar CV
                if (isset($_FILES['cv']) && $_FILES['cv']['error'] === 0) {
                    $cvName = time() . '_' . basename($_FILES['cv']['name']);
                    $cvPath = 'uploads/cv/' . $cvName;

                    // Crear carpeta si no existe
                    if (!is_dir('uploads/cv')) {
                        mkdir('uploads/cv', 0777, true);
                    }

                    move_uploaded_file($_FILES['cv']['tmp_name'], $cvPath);

                    $postulanteModel = new Postulante();
                    $resultado = $postulanteModel->guardarPostulacion($idPostulante, $idConvocatoria, $cvPath);

                    if ($resultado == 1) {
                        $_SESSION['success'] = "Postulación realizada correctamente.";
                    } elseif ($resultado == 2) {
                        $_SESSION['error'] = "Ya te postulaste a esta convocatoria.";
                    } else {
                        $_SESSION['error'] = "Error al postular. Intente nuevamente.";
                    }
                } else {
                    $_SESSION['error'] = "Debes adjuntar tu CV.";
                }

                header("Location: index.php?controller=postulante&action=convocatorias");
                exit;
            }
        }

        public function postulaciones() {
            $idPostulante = $_SESSION['user']['IdPostulante'] ?? null;

            if (!$idPostulante) {
                $_SESSION['error'] = "Error: No se identificó al postulante.";
                header("Location: index.php?controller=postulante&action=index");
                exit;
            }

            $postulanteModel = new Postulante();
            $postulaciones = $postulanteModel->listarPostulaciones($idPostulante);

            require_once __DIR__ . '/../views/layouts/header.php';
            require_once __DIR__ . '/../views/postulante/postulaciones.php';
            require_once __DIR__ . '/../views/layouts/footer.php';
        }

        public function detalleConvocatoria() {
            $idConvocatoria = $_GET['idConvocatoria'] ?? null;

            if (!$idConvocatoria) {
                $_SESSION['error'] = "No se encontró la convocatoria.";
                header("Location: index.php?controller=postulante&action=postulaciones");
                exit;
            }

            $postulanteModel = new Postulante();
            $convocatoria = $postulanteModel->ConvocatoriaDetalle($idConvocatoria);

            if (!$convocatoria) {
                $_SESSION['error'] = "Convocatoria no encontrada.";
                header("Location: index.php?controller=postulante&action=postulaciones");
                exit;
            }

            require_once __DIR__ . '/../views/layouts/header.php';
            require_once __DIR__ . '/../views/postulante/detalle_convocatoria.php';
            require_once __DIR__ . '/../views/layouts/footer.php';
        }



}
