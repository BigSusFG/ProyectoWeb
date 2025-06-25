<?php
session_start();

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "expoescom2025");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$isAdmin = isset($_SESSION["admin"]) && strpos($_SERVER['HTTP_REFERER'] ?? '', 'paginaAdmin.php') !== false;

// Captura y escapa de datos del formulario
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
$horario = $_POST["horario"]; // Preferencia: Matutino o Vespertino
$nombreProyecto = $_POST["nombreProyecto"];
$nombreEquipo = $_POST["nombreEquipo"];

// Verificar si la boleta ya está registrada
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

// === LÓGICA DE ASIGNACIÓN DE SALÓN Y HORARIO ===
$salones = ["2103", "2104", "2105", "2106", "2107"];
$fechaAsignada = "2025-06-20"; // Fecha única de exposición

$horariosMatutinos = ["10:30:00", "12:00:00"];
$horariosVespertinos = ["15:00:00", "16:30:00"];
$preferencia = $horario;

$horarios = $preferencia === "Matutino" ? $horariosMatutinos : $horariosVespertinos;
$alternativa = $preferencia === "Matutino" ? $horariosVespertinos : $horariosMatutinos;

$salonAsignado = null;
$horaAsignada = null;

// Función para buscar disponibilidad
function buscarDisponible($conexion, $salones, $horarios, $fecha, &$salonOut, &$horaOut, &$fechaOut) {
    foreach ($salones as $salon) {
        foreach ($horarios as $hora) {
            $sql = "SELECT COUNT(*) as total FROM participantes WHERE salon = ? AND hora_expo = ? AND fecha_expo = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sss", $salon, $hora, $fecha);
            $stmt->execute();
            $resultado = $stmt->get_result()->fetch_assoc();
            if ($resultado["total"] < 1) {
                $salonOut = $salon;
                $horaOut = $hora;
                $fechaOut = $fecha;
                return true;
            }
        }
    }
    return false;
}

// Intentar con el turno preferido
if (!buscarDisponible($conexion, $salones, $horarios, $fechaAsignada, $salonAsignado, $horaAsignada, $fechaAsignada)) {
    // Intentar con el turno alternativo
    if (!buscarDisponible($conexion, $salones, $alternativa, $fechaAsignada, $salonAsignado, $horaAsignada, $fechaAsignada)) {
        // Si no hay espacio en ningún turno
        echo "error:no_hay_espacio";
        $conexion->close();
        exit();
    }
}

// Insertar el participante con los datos + asignaciones
$sql = "INSERT INTO participantes (
    boleta, nombre, ap_paterno, ap_materno, genero, curp, telefono, semestre, carrera,
    correo, contrasena, academia, unidad_aprendizaje, horario,
    nombre_proyecto, nombre_equipo, salon, hora_expo, fecha_expo
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
    "sssssssisssssssssss",
    $boleta, $nombre, $apPat, $apMat, $genero, $curp, $telefono, $semestre, $carrera,
    $correo, $contrasena, $academia, $unidad, $horario,
    $nombreProyecto, $nombreEquipo, $salonAsignado, $horaAsignada, $fechaAsignada
);

// Guardar en la base de datos
if ($stmt->execute()) {
    if ($isAdmin) {
        header("Location: ../admin/paginaAdmin.php"); // o la ruta relativa correcta
        exit();
    } else {
        $_SESSION["boleta"] = $boleta;
        header("Location: perfilParticipante.php");
        exit();
    }
}

/* ── SOLO llega aquí si execute() falló ─────────── */
if ($stmt->errno == 1062) {                       // clave duplicada
    if (str_contains($stmt->error, 'boleta')) {
        echo 'error:boleta_duplicada';
    } elseif (str_contains($stmt->error, 'curp')) {
        echo 'error:curp_duplicada';
    } elseif (str_contains($stmt->error, 'correo')) {
        echo 'error:correo_duplicado';
    } else {
        echo 'error:duplicado_desconocido';
    }
} else {                                          // otro error SQL
    echo 'error:sql_generico';
}
$stmt->close();
$conexion->close();
exit();
?>
