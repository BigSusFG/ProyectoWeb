<?php
require('/ProyectoWeb/fpdf/fpdf186/fpdf.php');
ob_start();

class PDF extends FPDF
{
    function Header()
    {
        // Fondo elegante pastel
        $this->Image('imgs/fondo.jpg', 0, 0, 297, 210); // Fondo completo A4 horizontal

        // Logos ajustados al margen interno
        $this->Image('imgs/ipnlogo.png', 15, 12, 25); // Izquierda ajustada
        $this->Image('imgs/escudoESCOM.png', 257, 12, 25); // Derecha ajustada

        // Título principal
        $this->SetFont('Arial','B',40);
        $this->SetTextColor(10, 30, 90);
        $this->Ln(25);
        $this->Cell(0,20,utf8_decode('DIPLOMA'),0,1,'C');

        // Subtítulo decorativo en Great Vibes
        $this->AddFont('GreatVibes','','GreatVibes-Regular.php');
        $this->SetFont('GreatVibes','',36);
        $this->SetTextColor(128, 52, 162); // Color elegante, puedes ajustarlo a tu gusto
        $this->Cell(0,20,utf8_decode('Reconocimiento al 1er lugar EXPO-ESCOM 2025'),0,1,'C');
        $this->Ln(8);
    }

    function Footer()
    {
        $this->SetY(-25);
        $this->SetFont('Arial','I',10);
        $this->SetTextColor(50, 50, 50);
        $this->Cell(0,10,utf8_decode('Exposición de Proyectos ESCOM - IPN 2025'),0,1,'C');
        $this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// Conexión
$conexion = mysqli_connect('localhost', 'root', '', 'expoescom2025');
$boleta = $_GET['boleta'] ?? '2023030484';

$sql = "SELECT * FROM participantes WHERE boleta='$boleta'";
$resultado = mysqli_query($conexion, $sql);

if (!$resultado || mysqli_num_rows($resultado) == 0) {
    die("No se encontró un participante con la boleta: $boleta");
}

$alumno = mysqli_fetch_array($resultado);

// Crear PDF
$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddFont('GreatVibes','','fpdf/fpdf186/font/GreatVibes-Regular.php'); // Asegura que esté registrada
$pdf->AliasNbPages();
$pdf->AddPage();

// Texto elegante en cuerpo
$pdf->SetFont('GreatVibes','',24);
$pdf->SetTextColor(30,30,30);
$pdf->MultiCell(0,10,utf8_decode("Se otorga este diploma como testimonio de su compromiso, dedicación y esfuerzo, así como de su logro académico de alto nivel, al equipo \"{$alumno['nombre_equipo']}\", del semestre \"{$alumno['semestre']}\", de la carrera \"{$alumno['carrera']}\", de la unidad de aprendizaje \"{$alumno['unidad_aprendizaje']}\", por su proyecto \"{$alumno['nombre_proyecto']}\".\n\nEnhorabuena por su dedicación y logro."), 0, 'C');

$pdf->Ln(8);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,utf8_decode("Fecha de entrega: ") . date("d/m/Y"), 0, 1, 'C');

$pdf->Output();
ob_end_flush();