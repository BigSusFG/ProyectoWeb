<?php
session_start();
require('../fpdf186/fpdf.php');

if (!isset($_SESSION["boleta"])) {
    die("Acceso denegado. Debes iniciar sesión.");
}

$conexion = mysqli_connect('localhost', 'root', '', 'expoescom2025');
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

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

class PDF extends FPDF {
    function Header() {
        $this->Image('../../imgs/logo.png', 10, 8, 190);
        $this->Ln(30);
    }

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Times', 'I', 10);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Datos del Participante'), 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Boleta:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['boleta']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Nombre:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['nombre']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Apellido Paterno:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['ap_paterno']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Apellido Materno:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['ap_materno']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Género:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['genero']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('CURP:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['curp']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Teléfono:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['telefono']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Semestre:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['semestre']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Carrera:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['carrera']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Correo:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['correo']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Academia:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['academia']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Unidad de Aprendizaje:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['unidad_aprendizaje']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Horario:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['horario']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Nombre del Proyecto:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['nombre_proyecto']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Nombre del Equipo:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['nombre_equipo']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Fecha de Registro:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['fecha_registro']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Salón:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['salon']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Fecha de Exposición:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['fecha_expo']), 0, 'J');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(50, 8, utf8_decode('Hora de Exposición:'), 0, 0);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(0, 8, utf8_decode($participante['hora_expo']), 0, 'J');

$pdf->Output('diploma.pdf', 'D');
?>
