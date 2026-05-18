<?php
// controllers/EvaluadorController.php
require_once __DIR__ . '/../models/Evaluador.php';

class EvaluadorController {
    private $model;

    public function __construct() {
        $this->model = new Evaluador();
        // Verificación de sesión
        if (!isset($_SESSION['user']) || $_SESSION['user']['IdPerfil'] != 3) {
            header("Location: index.php?controller=login&action=index");
            exit;
        }
    }

    public function index() {
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/evaluador/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    // ===== CRUD DE CONVOCATORIAS =====
    public function convocatorias() {
        $convocatorias = $this->model->listarConvocatorias();
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/evaluador/convocatorias.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
     
    public function createConvocatoria() {
        $areas = $this->model->listarAreas();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'titulo'            => $_POST['titulo'],
                'descripcion'       => $_POST['descripcion'],
                'fechaInicio'       => $_POST['fechaInicio'],
                'fechaFin'          => $_POST['fechaFin'],
                'idArea'            => $_POST['idArea'],
                'idUsuarioCreacion' => $_SESSION['user']['IdUsuario'] // Captura usuario logueado
            ];

            if ($this->model->createConvocatoria($data)) {
                $_SESSION['success'] = 'Convocatoria creada exitosamente.';
            } else {
                $_SESSION['error'] = 'Error al crear la convocatoria.';
            }
            header('Location: index.php?controller=evaluador&action=convocatorias');
            exit;
        }

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/evaluador/create_convocatoria.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

        public function editConvocatoria($id) {
        $convocatoria = $this->model->getConvocatoriaById($id);
        $areas = $this->model->listarAreas();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'titulo' => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'],
                'fechaInicio' => $_POST['fechaInicio'],
                'fechaFin' => $_POST['fechaFin'],
                'idArea' => $_POST['idArea'],
                'idUsuarioModificacion' => $_SESSION['user']['IdUsuario']
            ];

            if ($this->model->updateConvocatoria($id, $data)) {
                $_SESSION['success'] = 'Convocatoria actualizada exitosamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar la convocatoria.';
            }
            header('Location: index.php?controller=evaluador&action=convocatorias');
            exit;
        }

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/evaluador/edit_convocatoria.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function deleteConvocatoria($id) {
        if ($this->model->deleteConvocatoria($id)) {
            $_SESSION['success'] = 'Convocatoria eliminada exitosamente.';
        } else {
            $_SESSION['error'] = 'Error al eliminar la convocatoria.';
        }
        header('Location: index.php?controller=evaluador&action=convocatorias');
        exit;
    }

        // ===== CRUD AREA =====
    public function areas() {
        $areas = $this->model->listarTodasAreas();
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/evaluador/areas.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function createArea() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'idUsuarioCreacion' => $_SESSION['user']['IdUsuario']
            ];
            if ($this->model->createArea($data)) {
                $_SESSION['success'] = 'Área creada exitosamente.';
            } else {
                $_SESSION['error'] = 'Error al crear el área.';
            }
            header('Location: index.php?controller=evaluador&action=areas');
            exit;
        }

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/evaluador/create_area.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function editArea($id) {
        $area = $this->model->getAreaById($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'idUsuarioModificacion' => $_SESSION['user']['IdUsuario']
            ];
            if ($this->model->updateArea($id, $data)) {
                $_SESSION['success'] = 'Área actualizada exitosamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar el área.';
            }
            header('Location: index.php?controller=evaluador&action=areas');
            exit;
        }

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/evaluador/edit_area.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function deleteArea($id) {
        if ($this->model->deleteArea($id)) {
            $_SESSION['success'] = 'Área eliminada exitosamente.';
        } else {
            $_SESSION['error'] = 'Error al eliminar el área.';
        }
        header('Location: index.php?controller=evaluador&action=areas');
        exit;
    }

        // Mostrar convocatorias con botón de ver postulaciones
    public function verConvocatorias() {
        $areas = $this->model->listarAreas();
        $convocatorias = $this->model->listarConvocatoriasActivas();
        error_log("verConvocatorias: Áreas obtenidas, count=" . count($areas) . ", Convocatorias obtenidas, count=" . count($convocatorias));
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/evaluador/ver_convocatorias.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

   public function verPostulaciones($idConvocatoria, $numeroDocumento = '') {
        if (empty($idConvocatoria) || !is_numeric($idConvocatoria)) {
            $_SESSION['error'] = 'ID de convocatoria inválido.';
            header("Location: index.php?controller=evaluador&action=verConvocatorias");
            exit;
        }
        $numeroDocumento = trim($numeroDocumento);
        error_log("verPostulaciones: idConvocatoria=$idConvocatoria, numeroDocumento='$numeroDocumento'");
        if (empty($numeroDocumento)) {
            $postulaciones = $this->model->listarPostulacionesPorConvocatoria($idConvocatoria);
        } else {
            $postulaciones = $this->model->listarPostulacionesPorNumeroDocumento($idConvocatoria, $numeroDocumento);
        }
        error_log("verPostulaciones: idConvocatoria=$idConvocatoria, numeroDocumento='$numeroDocumento', Postulaciones obtenidas, count=" . count($postulaciones));
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/evaluador/postulaciones.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function postulacionesPorNumeroDocumento() {
    if (!isset($_GET['idConvocatoria']) || !is_numeric($_GET['idConvocatoria'])) {
        $_SESSION['error'] = "❌ ID de convocatoria inválido.";
        header("Location: index.php?controller=evaluador&action=verConvocatorias");
        exit;
    }

    $idConvocatoria   = (int) $_GET['idConvocatoria'];
    $numeroDocumento  = isset($_GET['numeroDocumento']) ? trim($_GET['numeroDocumento']) : '';

    $model = new Evaluador();
    $postulaciones = $model->listarPostulacionesPorDocumento($idConvocatoria, $numeroDocumento);

    // cargar la vista correcta
    require_once __DIR__ . '/../views/layouts/header.php';
    require_once __DIR__ . '/../views/evaluador/postulaciones.php';
    require_once __DIR__ . '/../views/layouts/footer.php';
}



    public function evaluarPostulacion($id = null) {
        if (empty($id) || !is_numeric($id)) {
            die("❌ ID de postulación inválido.");
        }

        $postulacion = $this->model->obtenerPostulacion($id);
        $etapas = $this->model->listarEtapas();

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/evaluador/evaluar.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function guardarEvaluacion() {
        $data = [
            "IdPostulacion" => $_POST["IdPostulacion"],
            "IdEtapa" => $_POST["IdEtapa"],
            "Calificacion" => $_POST["Calificacion"],
            "Comentario" => $_POST["Comentario"],
            "IdEvaluador" => $_SESSION['user']['IdUsuario'],
            "IdUsuarioCreacion" => $_SESSION['user']['IdUsuario']
        ];

        if ($this->model->insertarEvaluacion($data)) {
            $_SESSION['success'] = "✅ Evaluación guardada y estado actualizado.";
        } else {
            $_SESSION['error'] = "❌ Error al guardar la evaluación.";
        }

        header("Location: index.php?controller=evaluador&action=verPostulaciones&idConvocatoria=" . $_POST["IdConvocatoria"]);
        exit;
    }

    public function editarEvaluacion($id) {
        if (empty($id) || !is_numeric($id)) {
            die("❌ ID de evaluación inválido.");
        }

        $evaluacion = $this->model->getEvaluacionById($id);
        $etapas = $this->model->listarEtapas();

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/evaluador/editar_evaluacion.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

public function actualizarEvaluacion() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            "IdEvaluacion"  => $_POST["IdEvaluacion"],
            "IdPostulacion" => $_POST["IdPostulacion"],
            "IdEtapa"       => $_POST["IdEtapa"],
            "Calificacion"  => $_POST["Calificacion"],
            "Comentario"    => $_POST["Comentario"],
            "IdEvaluador"   => $_SESSION["usuario_id"] ?? 1
        ];

        $ok = $this->model->actualizarEvaluacion($data);

        if ($ok) {
            $_SESSION['success'] = "✅ Evaluación actualizada y estado de la postulación ajustado.";
        } else {
            $_SESSION['error'] = "❌ Error al actualizar la evaluación.";
        }

        header("Location: index.php?controller=evaluador&action=listarEvaluaciones&id={$data["IdPostulacion"]}&idConvocatoria={$_POST["IdConvocatoria"]}");
        exit;
    }
}

public function listarEvaluaciones() {
    $idPostulacion = $_GET['id'] ?? null;
    if (empty($idPostulacion) || !is_numeric($idPostulacion)) {
        die("❌ ID de postulación inválido.");
    }

    $evaluaciones = $this->model->listarEvaluacionesPorPostulacion($idPostulacion);

    require_once __DIR__ . '/../views/layouts/header.php';
    require_once __DIR__ . '/../views/evaluador/lista_evaluaciones.php';
    require_once __DIR__ . '/../views/layouts/footer.php';
}

public function rechazarPostulacion($id = null) {
        error_log("rechazarPostulacion: id=$id, idConvocatoria=" . ($_GET['idConvocatoria'] ?? 'no definido') . ", IdUsuario=" . ($_SESSION['user']['IdUsuario'] ?? 'no definido'));
        if (empty($id) || !is_numeric($id)) {
            $_SESSION['error'] = 'ID de postulación inválido.';
            header("Location: index.php?controller=evaluador&action=verConvocatorias");
            exit;
        }
        $postulacion = $this->model->obtenerPostulacion($id);
        if (empty($postulacion)) {
            $_SESSION['error'] = 'Postulación no encontrada.';
            header("Location: index.php?controller=evaluador&action=verConvocatorias");
            exit;
        }
        if ($postulacion['Estado'] === 'descartado' || $postulacion['Estado'] === 'seleccionado') {
            $_SESSION['error'] = 'No se puede rechazar una postulación que ya está descartada o seleccionada.';
            header("Location: index.php?controller=evaluador&action=verPostulaciones&idConvocatoria=" . $postulacion['IdConvocatoria']);
            exit;
        }
        $data = [
            'IdPostulacion' => $id,
            'IdUsuarioModificacion' => $_SESSION['user']['IdUsuario']
        ];
        if ($this->model->rechazarPostulacion($data)) {
            $_SESSION['success'] = 'Postulación rechazada exitosamente.';
        } else {
            $_SESSION['error'] = 'Error al rechazar la postulación.';
        }
        header("Location: index.php?controller=evaluador&action=verPostulaciones&idConvocatoria=" . $postulacion['IdConvocatoria']);
        exit;
    }

   public function convocatoriasPorArea() {
        $idArea = isset($_GET['idArea']) && is_numeric($_GET['idArea']) ? (int)$_GET['idArea'] : 0;
        error_log("convocatoriasPorArea: idArea recibido=" . (isset($_GET['idArea']) ? $_GET['idArea'] : 'no definido'));
        $areas = $this->model->listarAreas();
        $convocatorias = $this->model->listarConvocatoriasPorArea($idArea);
        error_log("convocatoriasPorArea: idArea=$idArea, Áreas obtenidas, count=" . count($areas) . ", Convocatorias obtenidas, count=" . count($convocatorias));
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/evaluador/ver_convocatorias.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

     // Controller
public function detallePostulante() {
    $idPostulante = $_GET['idPostulante'] ?? '';
    $idConvocatoria = $_GET['idConvocatoria'] ?? '';

    if (empty($idPostulante) || !is_numeric($idPostulante)) {
        $_SESSION['error'] = "ID de postulante inválido.";
        header("Location: index.php?controller=evaluador&action=verConvocatorias");
        exit;
    }

    $postulante = $this->model->getDetallePostulante((int)$idPostulante);

    if (!$postulante) {
        $_SESSION['error'] = "Postulante no encontrado o eliminado.";
        header("Location: index.php?controller=evaluador&action=verConvocatorias");
        exit;
    }

    require_once __DIR__ . '/../views/layouts/header.php';
    require_once __DIR__ . '/../views/evaluador/detalle_postulante.php';
    require_once __DIR__ . '/../views/layouts/footer.php';
}
}

?>