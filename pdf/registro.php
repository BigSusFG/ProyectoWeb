<?php
session_start();
require('../fpdf/fpdf.php');

// Verificar que hay sesión activa
if (!isset($_SESSION["boleta"])) {
    die("Acceso denegado. Debes iniciar sesión.");
}

// Conexión a la BD
$conexion = mysqli_connect('localhost', 'root', '', 'expoescom2025');
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Obtener datos del participante por boleta
$boleta = $_SESSION["boleta"];
$sql = "SELECT * FROM participantes WHERE boleta = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $boleta);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$participante = mysqli_fetch_assoc($resultado);

if (!$participante) {
    die("No se encontró al participante.");
}

// Crear clase PDF personalizada
class PDF extends FPDF {
    function Header() {
        $this->Image('logo.png', 10, 8, 190); // ancho ajustado
        $this->Ln(30);
    }

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('helvetica', 'I', 10);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Crear PDF
$pdf = new PDF();
$pdf->AliasNBPages();
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Datos del Participante', 0, 1, 'C');
$pdf->Ln(10);

// Mostrar datos
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(50, 10, 'Boleta:', 0, 0);
$pdf->Cell(0, 10, $participante['boleta'], 0, 1);

$pdf->Cell(50, 10, 'Nombre:', 0, 0);
$pdf->Cell(0, 10, $participante['nombre'], 0, 1);

$pdf->Cell(50, 10, 'Apellido Paterno:', 0, 0);
$pdf->Cell(0, 10, $participante['ap_paterno'], 0, 1);

$pdf->Cell(50, 10, 'Apellido Materno:', 0, 0);
$pdf->Cell(0, 10, $participante['ap_materno'], 0, 1);

$pdf->Cell(50, 10, 'Correo:', 0, 0);
$pdf->Cell(0, 10, $participante['correo'], 0, 1);

$pdf->Cell(50, 10, 'Teléfono:', 0, 0);
$pdf->Cell(0, 10, $participante['telefono'], 0, 1);

$pdf->Output();
?>
