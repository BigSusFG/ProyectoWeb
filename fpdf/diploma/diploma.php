<?php
session_start();
require('../fpdf186/fpdf.php');
ob_start();

// Validar sesión
if (!isset($_SESSION["boleta"])) {
    die("Acceso denegado. Debes iniciar sesión.");
}

$boleta = $_SESSION["boleta"];

// Conexión a la base de datos
$conexion = mysqli_connect('localhost', 'root', '', 'expoescom2025');
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Buscar solo si el usuario ha sido marcado como ganador
$sql = "SELECT * FROM participantes WHERE boleta = ? AND ganador = 1";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $boleta);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (!$resultado || mysqli_num_rows($resultado) == 0) {
    die("Este usuario no tiene permitido generar su diploma.");
}

$alumno = mysqli_fetch_array($resultado);

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../../imgs/fondo.jpg', 0, 0, 297, 210);
        $this->Image('../../imgs/ipnlogo.png', 15, 12, 25);
        $this->Image('../../imgs/escudoESCOM.png', 257, 12, 25);

        $this->SetFont('Times', 'B', 40);
        $this->SetTextColor(10, 30, 90);
        $this->Ln(25);
        $this->Cell(0, 20, iconv('UTF-8', 'windows-1252', 'DIPLOMA'), 0, 1, 'C');

        $this->AddFont('GreatVibes', '', 'GreatVibes-Regular.php');
        $this->SetFont('GreatVibes', '', 36);
        $this->SetTextColor(128, 52, 162);
        $this->Cell(0, 20, iconv('UTF-8', 'windows-1252', 'Reconocimiento al 1er lugar EXPO-ESCOM 2025'), 0, 1, 'C');
        $this->Ln(8);
    }

    function Footer()
    {
        $this->SetY(-25);
        $this->SetFont('Times', 'I', 10);
        $this->SetTextColor(50, 50, 50);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Exposición de Proyectos ESCOM - IPN 2025'), 0, 1, 'C');
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddFont('GreatVibes', '', 'GreatVibes-Regular.php');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('GreatVibes', '', 24);
$pdf->SetTextColor(30, 30, 30);

$texto = "Se otorga este diploma como testimonio de su compromiso, dedicación y esfuerzo, así como de su logro académico de alto nivel, al equipo \"{$alumno['nombre_equipo']}\", del semestre \"{$alumno['semestre']}\", de la carrera \"{$alumno['carrera']}\", de la unidad de aprendizaje \"{$alumno['unidad_aprendizaje']}\", por su proyecto \"{$alumno['nombre_proyecto']}\".\n\nEnhorabuena por su dedicación y logro.";

$pdf->MultiCell(0, 10, iconv('UTF-8', 'windows-1252', $texto), 0, 'C');
$pdf->Ln(8);

$pdf->SetFont('Times', '', 12);
$pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Fecha de entrega: ') . date("d/m/Y"), 0, 1, 'C');

$pdf->Output();
ob_end_flush();