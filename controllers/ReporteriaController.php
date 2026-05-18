<?php
require_once __DIR__ . '/../models/Convocatoria.php';
require_once __DIR__ . '/../libs/fpdf.php';

class PDFResultadosGenerales extends FPDF {
    protected $logoPath;
    protected $fechaGeneracion;
     protected $angle = 0;

    public function __construct($logoPath) {
        parent::__construct();
        $this->logoPath = $logoPath;
        $this->fechaGeneracion = date('d/m/Y H:i');
    }

    // Encabezado que incluye logo, título y fecha
    function Header() {
        // Insertar logo si existe
        if (file_exists($this->logoPath)) {
            $this->Image($this->logoPath, 10, 6, 30);
        }

        // Fuente y color para el título
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(0, 51, 102); // Azul oscuro
        $this->Cell(40);
        $this->Cell(110, 10, utf8_decode('Reporte General de Postulantes Seleccionados'), 0, 0, 'C');
        $this->Ln(12);

        // Fecha de generación del reporte
        $this->SetFont('Arial', 'I', 10);
        $this->SetTextColor(100);
        $this->Cell(0, 6, utf8_decode('Fecha de generación: ') . $this->fechaGeneracion, 0, 1, 'C');
        $this->Ln(4);

        // Línea horizontal para separar encabezado
        $this->SetDrawColor(0, 51, 102);
        $this->SetLineWidth(0.7);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5);
    }

    // Pie de página con número de página y copyright
    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 9);
        $this->SetTextColor(128);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . ' | © ' . date('Y') . ' Sistema de Convocatorias', 0, 0, 'C');
    }

    // Función para dibujar una marca de agua semi-transparente
    function marcaDeAgua($texto = 'CONFIDENCIAL') {
        $this->SetFont('Arial', 'B', 50);
        $this->SetTextColor(200, 200, 200); // Gris claro
        $this->RotatedText(35, 190, $texto, 45);
        // Reset color para no afectar lo demás
        $this->SetTextColor(0);
    }

    // Función para texto rotado (para la marca de agua)
    function RotatedText($x, $y, $txt, $angle) {
        // Rotar texto y dibujar
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

    // Función para aplicar rotación (adaptación de FPDF)
    function Rotate($angle, $x = -1, $y = -1) {
        if ($x == -1)
            $x = $this->x;
        if ($y == -1)
            $y = $this->y;
        if ($this->angle != 0)
            $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.5F %.5F cm 1 0 0 1 %.5F %.5F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    // Función para imprimir tabla de resultados con estilos y líneas
    public function tablaResultados($header, $data) {
        $w = [50, 35, 50, 30, 25]; // Ancho de cada columna
        $lineHeight = 5; // Altura base por línea de texto

        // Imprimir cabecera con fondo azul claro
        $this->SetFont('Arial', 'B', 11);
        $this->SetFillColor(173, 216, 230); 
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 51, 102); 

        foreach ($header as $i => $col) {
            $this->Cell($w[$i], 8, utf8_decode($col), 1, 0, 'C', true);
        }
        $this->Ln();

        // Imprimir datos fila por fila con fondo alternado
        $this->SetFont('Arial', '', 10);
        $fill = false;

        foreach ($data as $row) {
            $this->SetFillColor($fill ? 230 : 255);

            // Calcular líneas necesarias para cada celda para ajustar altura fila
            $lines = [];
            foreach ($w as $i => $width) {
                $campo = "";
                switch ($i) {
                    case 0: $campo = $row['Convocatoria']; break;
                    case 1: $campo = $row['Area']; break;
                    case 2: $campo = $row['NombrePostulante']; break;
                    case 3: $campo = $row['Evaluador']; break;
                    case 4: $campo = $row['NotaFinal']; break;
                }
                $lines[$i] = $this->NbLines($width, utf8_decode($campo));
            }

            $maxLines = max($lines);
            $rowHeight = $lineHeight * $maxLines;

            // Si no cabe en página, agregar nueva página
            if ($this->GetY() + $rowHeight > 260) {
                $this->AddPage();
                $this->marcaDeAgua(); // Volver a agregar marca de agua
            }

            $x = $this->GetX();
            $y = $this->GetY();

            // Imprimir celdas con MultiCell para texto envuelto y color de fondo
            $this->MultiCell($w[0], $lineHeight, utf8_decode($row['Convocatoria']), 0, 'L', $fill);
            $this->SetXY($x + $w[0], $y);

            $this->MultiCell($w[1], $lineHeight, utf8_decode($row['Area']), 0, 'L', $fill);
            $this->SetXY($x + $w[0] + $w[1], $y);

            $this->MultiCell($w[2], $lineHeight, utf8_decode($row['NombrePostulante']), 0, 'L', $fill);
            $this->SetXY($x + $w[0] + $w[1] + $w[2], $y);

            $this->MultiCell($w[3], $lineHeight, utf8_decode($row['Evaluador']), 0, 'L', $fill);
            $this->SetXY($x + $w[0] + $w[1] + $w[2] + $w[3], $y);

            $this->MultiCell($w[4], $lineHeight, utf8_decode($row['NotaFinal']), 0, 'C', $fill);

            // Dibujar rectángulos/bordes para cada celda
            for ($i = 0, $posX = $x; $i < count($w); $i++) {
                $this->Rect($posX, $y, $w[$i], $rowHeight);
                $posX += $w[$i];
            }

            // Mover cursor al final de la fila
            $this->SetXY($x, $y + $rowHeight);

            $fill = !$fill;
        }

        // Línea para cerrar tabla al final
        $this->SetDrawColor(0, 51, 102);
        $this->SetLineWidth(0.8);
        $this->Line(10, $this->GetY(), 10 + array_sum($w), $this->GetY());
    }

    function NbLines($w, $txt) {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
}

class ReporteriaController {
    private $modelConvocatoria;

    public function __construct() {
        $this->modelConvocatoria = new Convocatoria();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Validar que el usuario sea admin para acceder
    private function verificarSesion() {
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['IdPerfil']) || $_SESSION['user']['IdPerfil'] != 1) {
            $_SESSION['error'] = "Acceso denegado. Debes iniciar sesión como administrador.";
            header("Location: index.php?controller=login&action=index");
            exit;
        }
    }

    // Mostrar vista de resultados generales
    public function index() {
        $this->verificarSesion();

        $resultados = $this->modelConvocatoria->obtenerTodosResultadosGenerales();

        require_once __DIR__ . '/../views/admin/ResultadosGenerales.php';
    }

    // Exportar PDF con resultados generales, con marca de agua y texto extra
    public function exportarPdfResultadosGenerales() {
        $this->verificarSesion();

        $resultados = $this->modelConvocatoria->obtenerTodosResultadosGenerales();

        if (empty($resultados)) {
            echo "No hay resultados para generar el reporte.";
            exit;
        }

        $logoPath = __DIR__ . '/../libs/sise-logo.png';

        $pdf = new PDFResultadosGenerales($logoPath);
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 25);

        // Agregar marca de agua en la página inicial
        $pdf->marcaDeAgua('CONFIDENCIAL');

        // Texto introductorio detallado para explicar el reporte
        $textoIntro = "Este reporte presenta un resumen general y detallado de los postulantes seleccionados en todas las convocatorias activas. "
            . "El objetivo es brindar transparencia y facilidad para analizar los resultados obtenidos, mostrando la información clave: convocatoria, área, postulante, evaluador y nota final.";

        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 6, utf8_decode($textoIntro));
        $pdf->Ln(6);

        $header = ["Convocatoria", "Área", "Postulante", "Evaluador", "Nota Final"];

        // Imprimir tabla con resultados
        $pdf->tablaResultados($header, $resultados);

        // Mensaje de cierre
        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'I', 11);
        $pdf->SetTextColor(100);
        $pdf->Cell(0, 8, utf8_decode('--- Fin del reporte general ---'), 0, 1, 'C');

        // Salida del archivo PDF para descarga
        $filename = "resultados_generales_postulantes.pdf";
        $pdf->Output('D', $filename);
        exit;
    }
}
