<?php
// controllers/ConvocatoriaController.php

require_once __DIR__ . '/../models/Convocatoria.php';
require_once __DIR__ . '/../models/Area.php';
require_once __DIR__ . '/../libs/fpdf.php';

class PDFResultados extends FPDF {
    protected $logoPath;
    protected $tituloConvocatoria;
    protected $fechaGeneracion;

    public function __construct($logoPath, $tituloConvocatoria) {
        parent::__construct();
        $this->logoPath = $logoPath;
        $this->tituloConvocatoria = $tituloConvocatoria;
        $this->fechaGeneracion = date('d/m/Y H:i');
    }

    // Encabezado
    function Header() {
        // Logo si existe
        if (file_exists($this->logoPath)) {
            $this->Image($this->logoPath, 10, 6, 30);
        }
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(40);
        $this->Cell(110, 10, utf8_decode('Reporte de Postulantes Seleccionados'), 0, 0, 'C');
        $this->Ln(12);

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 6, utf8_decode('Convocatoria: ') . utf8_decode($this->tituloConvocatoria), 0, 1, 'C');
        $this->Cell(0, 6, utf8_decode('Fecha de generación: ') . $this->fechaGeneracion, 0, 1, 'C');
        $this->Ln(4);

        // Línea horizontal
        $this->SetDrawColor(0, 0, 0);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5);
    }

    // Pie de página
    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 9);
        $this->SetTextColor(128);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . ' | © ' . date('Y'), 0, 0, 'C');
    }

    // Agregar sección de justificación o criterios
    public function agregarCriterios($texto) {
        $this->SetFont('Arial', '', 11);
        $this->MultiCell(0, 6, utf8_decode($texto));
        $this->Ln(4);
    }

    // Agregar tabla con resultados
    public function tablaResultados($header, $data) {

        // Colocar encabezados
        $this->SetFont('Arial', 'B', 11);
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0, 0, 0);

        // Anchuras (ajusta a lo que consideres)
        $w = [50, 20, 35, 25, 30]; 

        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 8, utf8_decode($header[$i]), 1, 0, 'C', true);
        }
        $this->Ln();

        // Datos
        $this->SetFont('Arial', '', 10);
        $fill = false;
        foreach ($data as $row) {
            $this->SetFillColor($fill ? 240 : 255);
            $this->SetTextColor(0, 0, 0);

            // Supongamos $row tiene los campos en el mismo orden del encabezado
            $this->Cell($w[0], 8, utf8_decode($row['NombrePostulante']), 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 8, utf8_decode($row['TipoDocumento'] ?? ''), 'LR', 0, 'C', $fill);
            $this->Cell($w[2], 8, utf8_decode($row['NumeroDocumento']), 'LR', 0, 'C', $fill);
            $this->Cell($w[3], 8, utf8_decode($row['Celular']), 'LR', 0, 'C', $fill);
            $this->Cell($w[4], 8, utf8_decode($row['NotaFinal']), 'LR', 0, 'C', $fill);

            $this->Ln();
            $fill = !$fill;

            // Verificar si hay que hacer salto de página
            if ($this->GetY() > 260) {
                $this->AddPage();
            }
        }
        // Línea final inferior
        $this->Line(10, $this->GetY(), 10 + array_sum($w), $this->GetY());
    }
}


class ConvocatoriaController {
    private $model;
    private $areaModel;

    public function __construct($conn) {
        $this->model = new Convocatoria($conn);
        $this->areaModel = new Area($conn);

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

    // Panel de resumen
    public function panel() {
        $this->verificarSesion();

        $nombre = $_SESSION['user']['Nombre'];

        $totalConvocatoriasActivas = $this->model->contarConvocatoriasActivas() ?? 0;  

        include __DIR__ . '/../views/admin/index.php'; // Ajustado a tu estructura
    }

    // Mostrar vista principal del CRUD de convocatorias
    public function index() {
        $this->verificarSesion();

        $convocatorias = $this->model->obtenerTodas();

        $success = $_SESSION['success'] ?? null;
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['success'], $_SESSION['error']);

        include __DIR__ . '/../views/admin/CrudConvocatoria.php';
    }

    // Mostrar formulario para crear convocatoria
    public function crear() {
        $this->verificarSesion();

        $areas = $this->areaModel->obtenerTodas();
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        include __DIR__ . '/../views/admin/CrearConvocatoria.php';
    }

    // Guardar nueva convocatoria
    public function guardar() {
        $this->verificarSesion();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'Titulo' => trim($_POST['Titulo'] ?? ''),
                'Descripcion' => trim($_POST['Descripcion'] ?? ''),
                'FechaInicio' => $_POST['FechaInicio'] ?? '',
                'FechaFin' => $_POST['FechaFin'] ?? '',
                'IdArea' => intval($_POST['IdArea'] ?? 0),
                'IdUsuarioCreacion' => $_SESSION['user']['IdUsuario'] ?? 1
            ];

            if (empty($data['Titulo']) || empty($data['Descripcion']) || !$data['IdArea'] || empty($data['FechaInicio']) || empty($data['FechaFin'])) {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                header('Location: index.php?controller=convocatoria&action=crear');
                exit;
            }

            $resultado = $this->model->crear($data);
            if ($resultado === true) {
                $_SESSION['success'] = "Convocatoria creada correctamente.";
                header('Location: index.php?controller=convocatoria&action=index');
                exit;
            } else {
                $_SESSION['error'] = "Error al crear la convocatoria.";
                header('Location: index.php?controller=convocatoria&action=crear');
                exit;
            }
        }
    }

    // Mostrar formulario para editar convocatoria
    public function editar($id) {
        $this->verificarSesion();

        $convocatoria = $this->model->obtenerPorId($id);
        if (!$convocatoria) {
            $_SESSION['error'] = "Convocatoria no encontrada.";
            header('Location: index.php?controller=convocatoria&action=index');
            exit;
        }

        $areas = $this->areaModel->obtenerTodas();
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        include __DIR__ . '/../views/admin/EditarConvocatoria.php'; // Cambiado a la ruta correcta
    }

    // Actualizar convocatoria
    public function actualizar($id) {
        $this->verificarSesion();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'Titulo' => trim($_POST['Titulo'] ?? ''),
                'Descripcion' => trim($_POST['Descripcion'] ?? ''),
                'FechaInicio' => $_POST['FechaInicio'] ?? '',
                'FechaFin' => $_POST['FechaFin'] ?? '',
                'IdArea' => intval($_POST['IdArea'] ?? 0),
                'IdUsuarioModificacion' => $_SESSION['user']['IdUsuario'] ?? 1
            ];

            if (empty($data['Titulo']) || empty($data['Descripcion']) || !$data['IdArea'] || empty($data['FechaInicio']) || empty($data['FechaFin'])) {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                header("Location: index.php?controller=convocatoria&action=editar&id=$id");
                exit;
            }

            $resultado = $this->model->actualizar($id, $data);
            if ($resultado === true) {
                $_SESSION['success'] = "Convocatoria actualizada correctamente.";
                header('Location: index.php?controller=convocatoria&action=index');
                exit;
            } else {
                $_SESSION['error'] = "Error al actualizar la convocatoria.";
                header("Location: index.php?controller=convocatoria&action=editar&id=$id");
                exit;
            }
        }
    }

    // Eliminar convocatoria
    public function eliminar($id) {
        $this->verificarSesion();

        $idUsuario = $_SESSION['user']['IdUsuario'] ?? 1;
        $resultado = $this->model->eliminar($id, $idUsuario);

        if ($resultado === true) {
            $_SESSION['success'] = "Convocatoria eliminada correctamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar la convocatoria.";
        }

        header('Location: index.php?controller=convocatoria&action=index');
        exit;
    }


    // Buscar convocatorias por área
public function buscarPorArea() {
    $this->verificarSesion();

    $areas = $this->areaModel->obtenerTodas(); // Para llenar el combo
    $convocatorias = []; // Resultados

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['IdArea'])) {
        $idArea = intval($_POST['IdArea']);
        $convocatorias = $this->model->buscarPorArea($idArea);
    }

    include __DIR__ . '/../views/admin/BuscarConvocatoriasPorArea.php';
}

// Mostrar resultados de postulantes seleccionados por convocatoria
public function resultados($id) {
    $this->verificarSesion();

    $convocatoria = $this->model->obtenerPorId($id);
    if (!$convocatoria) {
        $_SESSION['error'] = "Convocatoria no encontrada.";
        header('Location: index.php?controller=convocatoria&action=index');
        exit;
    }

    $resultados = $this->model->obtenerResultadosPorConvocatoria($id);

    include __DIR__ . '/../views/admin/ResultadoPorConvocatoria.php';
}


// Metodo para exportar pdfs
public function exportarPdf() {
    $this->verificarSesion();

    // Validar que viene un ID por GET
    if (!isset($_GET['id'])) {
        echo "ID de convocatoria no proporcionado.";
        exit;
    }

    $id = intval($_GET['id']);

    // Obtener datos
    $convocatoria = $this->model->obtenerPorId($id);
    if (!$convocatoria) {
        echo "Convocatoria no encontrada.";
        exit;
    }

    $resultados = $this->model->obtenerResultadosPorConvocatoria($id);

    // Ruta al logo (ajústala si es necesario)
    $logoPath = __DIR__ . '/../libs/sise-logo.png';

    // Crear PDF
    $pdf = new PDFResultados($logoPath, $convocatoria['Titulo']);
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(true, 25);

    // Texto explicativo
    $textoCriterios = "Este reporte muestra los postulantes seleccionados para la convocatoria '" 
        . $convocatoria['Titulo'] 
        . "'. El proceso de evaluación se ha realizado con base en criterios técnicos previamente establecidos, "
        . "ponderaciones definidas, y revisado por el comité evaluador asignado. A continuación se presenta el detalle de los seleccionados y sus notas finales.";
    $pdf->agregarCriterios($textoCriterios);

    // Encabezados
    $header = [
        "Postulante",
        "Tipo Doc.",
        "Documento",
        "Celular",
        "Nota Final"
    ];
    $pdf->tablaResultados($header, $resultados);

    // Pie de cierre
    $pdf->Ln(8);
    $pdf->SetFont('Arial', 'I', 11);
    $pdf->Cell(0, 8, utf8_decode('--- Fin del reporte de resultados ---'), 0, 1, 'C');

    // Salida del PDF al navegador
    $filename = "resultados_convocatoria_{$id}.pdf";
    $pdf->Output('D', $filename);
    exit;
}

}
