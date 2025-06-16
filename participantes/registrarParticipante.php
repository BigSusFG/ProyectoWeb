<?php
session_start();

// Conexión
$conexion = new mysqli("localhost", "root", "", "expoescom2025");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Escapar y capturar datos del formulario
$boleta = $_POST["boleta"];
$nombre = $_POST["nombre"];
$apPat = $_POST["apPat"];
$apMat = $_POST["apMat"];
$genero = $_POST["genero"];
$curp = $_POST["curp"];
$telefono = $_POST["telefono"];
$semestre = $_POST["semestre"];
$carrera = $_POST["carrera"];
$correo = $_POST["correo"];
$contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);
$academia = $_POST["academia"];
$unidad = $_POST["unidadAprendizaje"];
$horario = $_POST["horario"];
$nombreProyecto = $_POST["nombreProyecto"];
$nombreEquipo = $_POST["nombreEquipo"];

// Verificar si ya existe una boleta registrada
$verificar = $conexion->prepare("SELECT 1 FROM participantes WHERE boleta = ?");
$verificar->bind_param("s", $boleta);
$verificar->execute();
$verificar->store_result();

if ($verificar->num_rows > 0) {
    echo "error:boleta_duplicada";
    $verificar->close();
    $conexion->close();
    exit();
}
$verificar->close();

// Insertar a la base de datos
$sql = "INSERT INTO participantes (
    boleta, nombre, ap_paterno, ap_materno, genero, curp, telefono, semestre, carrera,
    correo, contrasena, academia, unidad_aprendizaje, horario,
    nombre_proyecto, nombre_equipo
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssssissssssss", $boleta, $nombre, $apPat, $apMat, $genero, $curp, $telefono, $semestre, $carrera, $correo, $contrasena, $academia, $unidad, $horario, $nombreProyecto, $nombreEquipo);

if ($stmt->execute()) {
    $_SESSION["boleta"] = $boleta;
    header("Location: perfilParticipante.php");
    exit();
} else {
    echo "Error al registrar: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
